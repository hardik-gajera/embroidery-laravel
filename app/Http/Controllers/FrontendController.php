<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Design;
use App\Models\DesignPackage;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\ContactMessage;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function home()
    {
        $parentCategories = Category::parents()->withCount('children')->get();
        $featuredDesigns = Design::latest()->take(12)->get();
        $packages = DesignPackage::where('state', 'confirm')->latest()->take(4)->get();
        return view('frontend.home', compact('parentCategories', 'featuredDesigns', 'packages'));
    }

    public function allDesigns(Request $request)
    {
        $query = Design::with('category');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('design_code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('design_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('design_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }
        
        $designs = $query->paginate(20);
        $categories = Category::orderBy('name')->get();
        
        return view('frontend.all-designs', compact('designs', 'categories'));
    }

    public function packages()
    {
        $packages = DesignPackage::where('state', 'confirm')->get();
        return view('frontend.packages', compact('packages'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function contactSend(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email', 'message' => 'required']);
        ContactMessage::create($request->only('name', 'email', 'message'));
        return back()->with('success', 'Thank you! Your message has been sent.');
    }

    public function packageDetail($id)
    {
        $package = DesignPackage::findOrFail($id);
        return view('frontend.package-detail', compact('package'));
    }

    public function buyPackage(Request $request)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $package = DesignPackage::findOrFail($request->package_id);
        $customer = Customer::find(session('customer_id'));
        $razorpayKey = config('services.razorpay.key');
        $amount = $package->price * 100;

        return view('frontend.package-payment', compact('package', 'customer', 'razorpayKey', 'amount'));
    }

    public function packagePaymentSuccess(Request $request)
    {
        $package = DesignPackage::findOrFail($request->package_id);
        $customer = Customer::find(session('customer_id'));

        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addMonths($package->time_period);

        $customer->update([
            'package_id' => $package->id,
            'package_start_date' => $startDate,
            'package_end_date' => $endDate,
            'total_design' => $package->number_of_design,
            'downloaded_design' => 0,
        ]);

        Order::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'amount' => $request->amount / 100,
            'razorpay_order_id' => $request->razorpay_order_id ?? '',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'paid',
        ]);

        return redirect('/')->with('success', 'Package purchased successfully!');
    }

    public function categories($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $children = $category->children;

        if ($children->isEmpty()) {
            $designs = Design::where('category_id', $id)->paginate(12);
            return view('frontend.designs', compact('category', 'designs'));
        }

        $designs = Design::where('category_id', $id)->get();
        return view('frontend.categories', compact('category', 'children', 'designs'));
    }

    public function designs($id, Request $request)
    {
        $category = Category::findOrFail($id);
        $query = Design::with('category')->where('category_id', $id);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('design_code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('design_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('design_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }
        
        $designs = $query->paginate(20)->withQueryString();
        return view('frontend.designs', compact('category', 'designs'));
    }

    public function designDetail($id)
    {
        $design = Design::with('category')->findOrFail($id);
        $related = Design::where('category_id', $design->category_id)->where('id', '!=', $id)->take(4)->get();
        return view('frontend.design-detail', compact('design', 'related'));
    }

    public function showLogin()
    {
        return view('frontend.login');
    }

    public function login(Request $request)
    {
        $request->validate(['mobile_no' => 'required', 'password' => 'required']);
        $customer = Customer::where('mobile_no', $request->mobile_no)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            session(['customer_id' => $customer->id, 'customer_name' => $customer->name]);
            session(['cart_count' => Cart::where('customer_id', $customer->id)->count()]);

            // Clear any intended URL that might redirect to admin
            $intended = session()->pull('url.intended');
            
            // Only use intended URL if it's not an admin route
            if ($intended && !str_contains($intended, '/admin')) {
                return redirect($intended)->with('success', 'Welcome back!');
            }
            
            return redirect()->route('home')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['mobile_no' => 'Invalid mobile number or password.'])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'mobile_no' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Customer::create($request->only('name', 'email', 'mobile_no', 'password'));
        session(['customer_id' => $customer->id, 'customer_name' => $customer->name, 'cart_count' => 0]);

        // Clear any admin-related intended URLs and redirect to home
        session()->forget('url.intended');
        return redirect()->route('home')->with('success', 'Account created successfully!');
    }

    public function logout()
    {
        session()->forget(['customer_id', 'customer_name', 'cart_count']);
        return redirect('/')->with('success', 'Logged out.');
    }

    public function showForgotPassword()
    {
        return view('frontend.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['mobile_no' => 'required']);
        
        $customer = Customer::where('mobile_no', $request->mobile_no)->first();
        
        if (!$customer) {
            return back()->withErrors(['mobile_no' => 'No account found with this mobile number.'])->withInput();
        }
        
        // Generate a simple 6-digit reset code
        $resetCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store reset code in session with expiry
        session([
            'password_reset_mobile' => $request->mobile_no,
            'password_reset_code' => $resetCode,
            'password_reset_expires' => now()->addMinutes(10)
        ]);
        
        // For demo purposes, show the code (in production, send SMS)
        return redirect()->route('frontend.reset-password')
            ->with('success', "Reset code sent! Use code: {$resetCode} (Valid for 10 minutes)");
    }

    public function showResetPassword()
    {
        if (!session('password_reset_mobile')) {
            return redirect()->route('frontend.forgot-password')
                ->with('error', 'Please request password reset first.');
        }
        
        return view('frontend.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_code' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        // Check if reset session is valid
        if (!session('password_reset_mobile') || !session('password_reset_code')) {
            return redirect()->route('frontend.forgot-password')
                ->with('error', 'Invalid reset session. Please try again.');
        }
        
        // Check if reset code is expired
        if (now()->gt(session('password_reset_expires'))) {
            session()->forget(['password_reset_mobile', 'password_reset_code', 'password_reset_expires']);
            return redirect()->route('frontend.forgot-password')
                ->with('error', 'Reset code has expired. Please request a new one.');
        }
        
        // Verify reset code
        if ($request->reset_code !== session('password_reset_code')) {
            return back()->withErrors(['reset_code' => 'Invalid reset code.'])->withInput();
        }
        
        // Update password
        $customer = Customer::where('mobile_no', session('password_reset_mobile'))->first();
        $customer->update(['password' => Hash::make($request->password)]);
        
        // Clear reset session
        session()->forget(['password_reset_mobile', 'password_reset_code', 'password_reset_expires']);
        
        return redirect()->route('frontend.login')
            ->with('success', 'Password reset successfully! Please login with your new password.');
    }

    public function cart()
    {
        $cartItems = Cart::with('design')->where('customer_id', session('customer_id'))->get();
        $total = $cartItems->sum(fn($item) => $item->design->design_price);
        return view('frontend.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request)
    {
        if (!session('customer_id')) {
            // Store current URL only if it's not an admin route
            $currentUrl = url()->previous();
            if (!str_contains($currentUrl, '/admin')) {
                session(['url.intended' => $currentUrl]);
            }
            return redirect()->route('frontend.login')->with('error', 'Please login first.');
        }

        Cart::firstOrCreate([
            'customer_id' => session('customer_id'),
            'design_id' => $request->design_id,
        ]);

        session(['cart_count' => Cart::where('customer_id', session('customer_id'))->count()]);

        return back()->with('success', 'Added to cart!');
    }

    public function removeFromCart($id)
    {
        Cart::where('id', $id)->where('customer_id', session('customer_id'))->delete();
        session(['cart_count' => Cart::where('customer_id', session('customer_id'))->count()]);
        return back()->with('success', 'Removed from cart.');
    }

    public function buyNow(Request $request)
    {
        if (!session('customer_id')) {
            // Store current URL only if it's not an admin route
            $currentUrl = url()->previous();
            if (!str_contains($currentUrl, '/admin')) {
                session(['url.intended' => $currentUrl]);
            }
            return redirect()->route('frontend.login');
        }

        $design = Design::findOrFail($request->design_id);
        $customer = Customer::find(session('customer_id'));

        // Check if already purchased
        $alreadyPurchased = Order::where('customer_id', $customer->id)
            ->where('design_id', $design->id)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('frontend.my-designs')->with('success', 'You already own this design. Download it from here.');
        }

        // Check active package
        if ($customer->hasActivePackage()) {
            if ($customer->downloaded_design < $customer->total_design) {
                // Free download via package
                return view('frontend.package-download', compact('design', 'customer'));
            } else {
                // Package limit exceeded
                $razorpayKey = config('services.razorpay.key');
                $amount = $design->design_price * 100;
                $packageExceeded = true;
                return view('frontend.payment', compact('design', 'customer', 'razorpayKey', 'amount', 'packageExceeded'));
            }
        }

        // No package — go to payment
        $razorpayKey = config('services.razorpay.key');
        $amount = $design->design_price * 100;
        $packageExceeded = false;

        return view('frontend.payment', compact('design', 'customer', 'razorpayKey', 'amount', 'packageExceeded'));
    }

    public function claimDesign(Request $request)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $customer = Customer::find(session('customer_id'));
        $design = Design::findOrFail($request->design_id);

        if (!$customer->hasActivePackage() || $customer->downloaded_design >= $customer->total_design) {
            return back()->with('error', 'Package limit exceeded.');
        }

        // Create order with 0 amount (package claim)
        Order::create([
            'customer_id' => $customer->id,
            'design_id' => $design->id,
            'amount' => 0,
            'razorpay_payment_id' => 'package_claim',
            'status' => 'paid',
        ]);

        $customer->increment('downloaded_design');

        Cart::where('customer_id', $customer->id)->where('design_id', $design->id)->delete();
        session(['cart_count' => Cart::where('customer_id', $customer->id)->count()]);

        return view('frontend.purchase-success', compact('design'));
    }

    public function paymentSuccess(Request $request)
    {
        Order::create([
            'customer_id' => session('customer_id'),
            'design_id' => $request->design_id,
            'amount' => $request->amount / 100,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'paid',
        ]);

        Cart::where('customer_id', session('customer_id'))->where('design_id', $request->design_id)->delete();
        session(['cart_count' => Cart::where('customer_id', session('customer_id'))->count()]);

        $design = Design::findOrFail($request->design_id);
        return view('frontend.purchase-success', compact('design'));
    }

    public function myDesigns()
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $orders = Order::with('design')
            ->where('customer_id', session('customer_id'))
            ->whereNotNull('design_id')
            ->latest()
            ->get();

        return view('frontend.my-designs', compact('orders'));
    }

    public function myPackages()
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $customer = Customer::with('package')->find(session('customer_id'));
        $packageOrders = Order::with('package')
            ->where('customer_id', session('customer_id'))
            ->whereNotNull('package_id')
            ->latest()
            ->get();

        return view('frontend.my-packages', compact('customer', 'packageOrders'));
    }

    public function downloadDesign($id)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $design = Design::findOrFail($id);
        $customer = Customer::find(session('customer_id'));

        // Check if customer purchased this design or has active package
        $hasPurchased = Order::where('customer_id', $customer->id)
            ->where('design_id', $id)
            ->where('status', 'paid')
            ->exists();

        $hasActivePackage = $customer->hasActivePackage() && $customer->downloaded_design < $customer->total_design;

        if (!$hasPurchased && !$hasActivePackage) {
            return back()->with('error', 'You do not have access to download this design.');
        }

        $filePath = storage_path('app/public/' . $design->emb_file);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Design file not available.');
        }

        // If downloading via package (not individually purchased), increment counter
        if (!$hasPurchased && $hasActivePackage) {
            $customer->increment('downloaded_design');
        }

        // Use design code as filename if available, otherwise use original name
        $fileName = $design->design_code ?: ($design->name ?: 'design');
        $extension = $design->design_format ?: pathinfo($design->file_name, PATHINFO_EXTENSION);
        
        // Ensure we have an extension
        if (!$extension) {
            $extension = 'emb'; // Default extension
        }
        
        $downloadName = $fileName . '.' . $extension;

        return response()->file($filePath, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
        ]);
    }
}
