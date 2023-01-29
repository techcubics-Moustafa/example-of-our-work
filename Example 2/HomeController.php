<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Events\GetNotificationAdmin;
use App\Helpers\CPU\FirebaseNotification;
use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Http\Requests\Api\ContactUsRequest;
use App\Http\Requests\Api\FavouriteRequest;
use App\Http\Requests\Api\JoinUsRequest;
use App\Http\Requests\Api\RateRequest;
use App\Http\Resources\BranchType\BranchTypeResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\DeliveryArea\DeliveryAreaResource;
use App\Http\Resources\Discount\DiscountDetailsResource;
use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\Favourite\ProductFavouriteResource;
use App\Http\Resources\Favourite\RestaurantFavouriteResource;
use App\Http\Resources\Governorate\GovernorateResource;
use App\Http\Resources\Home\LanguageResource;
use App\Http\Resources\Offer\OfferDetailsResource;
use App\Http\Resources\Offer\OfferResource;
use App\Http\Resources\Page\PageResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Rate\RateResource;
use App\Http\Resources\Region\RegionResource;
use App\Http\Resources\Save\SaveDetailsResource;
use App\Http\Resources\Save\SaveResource;
use App\Http\Resources\Restaurant\RestaurantResource;
use App\Http\Resources\SocialMedia\SocialMediaResource;
use App\Interfaces\Api\RateRepositoryInterface;
use App\Models\Admin;
use App\Models\BranchType;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Customer;
use App\Models\DeliveryArea;
use App\Models\Discount;
use App\Models\Favourite;
use App\Models\Governorate;
use App\Models\JoinUs;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Page;
use App\Models\Product;
use App\Models\Rate;
use App\Models\Region;
use App\Models\Restaurant;
use App\Models\Save;
use App\Models\SocialMedia;
use App\Models\User;
use App\Traits\ApiResponses;
use App\Traits\FavouriteTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public $offerIds = [];
    public $saveIds = [];
    use ApiResponses , FavouriteTrait ;
    private RateRepositoryInterface $rateRepositoryInterface;

    public function __construct( RateRepositoryInterface $rateRepositoryInterface)
    {
        $this->rateRepositoryInterface = $rateRepositoryInterface;
    }


    public function saveToken(Request $request)
    {
        User::find(1)->update(['device_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

    public function sendNotifications()
    {
        $user = User::find(1);
        $firebase = new FirebaseNotification();
        $firebase->to([$user->device_token]);
        $firebase->withTitle('Test web notification');
        $firebase->withBody('Test web notification');
        $firebase->withData([
            'send' => 'is come from web'
        ]);
        $firebase->asNotification();
    }

    public function languages()
    {
        try {
            return $this->success(LanguageResource::collection(languages()));
        } catch (\Exception $exception) {
            //DB::rollBack();
            return $this->failure($exception->getMessage());
        }
    }

    public function home()
    {
        $categories = Category::query()->orderBy('ranking')->limit(10)
            ->withCount(['products' => function (Builder $query) {
                $query->whereRelation('restaurant',  ['country_id'=>request('country_id'),'status'=>Status::Active->value]);
            }])->whereNull('parent_id')->status()->get();
        $offers = Offer::query()
            ->whereRelation('restaurant', 'country_id', '=', request('country_id'))
            ->with(['restaurant', 'images'])
            ->whereHas('restaurant', function ($q) {
                $q->status();
            })
            ->withCount(['rates'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->limit(10)->status();
        $query = $offers->get();
        $query->each(function ($offer) use ($offers) {

            $productIds = [];
            foreach ($offer->products as $product) {
                $productIds [] = $product['product_id'];
            }
            $checkProduct = Product::query()->whereStatus(Status::Not_Active->value)
                ->whereIn('id', $productIds)->count();

            if ($checkProduct > 0) {
                array_push($this->offerIds, $offer->id);
            }
        });

        $offers = $offers->whereNotIn('id', $this->offerIds)
            ->orderByDesc('created_at')->get();
        $discounts = Discount::query()
            ->whereRelation('restaurant', 'country_id', '=', request('country_id'))
            ->whereHas('restaurant', function ($q) {
                $q->status();
            })->whereHas('product', function ($q) {
                $q->status();
            })
            ->with(['restaurant','product' => function ($q) {
                $q->with('images');

            }])->withCount(['rates'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->limit(10)->status()->get();
        $saves = Save::query()
            ->whereRelation('restaurant', 'country_id', '=', request('country_id'))
            ->with(['restaurant', 'images'])
            ->whereHas('restaurant', function ($q) {
                $q->status();
            })
            ->withCount(['rates'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->where('from', '<=', Carbon::now()->format('H:i:s'))
            ->where('to', '>=', Carbon::now()->format('H:i:s'))
            ->limit(10)->status();
        $query = $saves->get();
        $query->each(function ($save) use ($saves) {

            $productIds = [];
            foreach ($save->products as $product) {
                $productIds [] = $product['product_id'];
            }
            $checkProduct = Product::query()->whereStatus(Status::Not_Active->value)
                ->whereIn('id', $productIds)->count();

            if ($checkProduct > 0) {
                array_push($this->saveIds, $save->id);
            }
        });

        $saves = $saves->whereNotIn('id', $this->saveIds)
            ->orderByDesc('created_at')->get();

        $branchTypes = BranchType::query()
            ->withCount(['restaurants'=>function($q){
                $q->status();
            }])->orderByDesc('created_at')->limit(10)->published()->status()->get();

        if (auth('sanctum')->check()) {
            $customer = auth('sanctum')->user()->userable;
            $notifications = Notification::query()->where([
                'receiverable_type' => Customer::class,
                'receiverable_id' => $customer->id,
            ])->whereIn('type', ['restaurant', 'global', 'coupon'])
                ->whereNull('read_at')->count();
        } else {
            $notifications = 0;
        }
        return $this->success([
            'categories' => CategoryResource::collection($categories),
            'offers' => OfferResource::collection($offers),
            'saves' => SaveResource::collection($saves),
            'discounts' => DiscountResource::collection($discounts),
            'branch_type' => BranchTypeResource::collection($branchTypes),
            'total_of_unread_notifications' => $notifications,

        ], message: _trans('Home Api'));

    }

    public function categories()
    {
        $categories = Category::query()->whereNull('parent_id')
            ->orderByDesc('ranking')
            ->withCount(['products' => function (Builder $query) {
                $query->whereRelation('restaurant',  ['country_id'=>request('country_id'),'status'=>Status::Active->value]);
            }])
            ->status()->get();
        return $this->success(CategoryResource::collection($categories), message: _trans('List of Categories'));
    }

    public function offers()
    {

        $offers = Offer::query()->status()
            ->whereRelation('restaurant', 'country_id', '=', request('country_id'))
            ->whereHas('restaurant', function ($q) {
                $q->status();
            })
            ->withCount(['rates'])->with(['images','restaurant'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->when('price',function ($q){
                if (request('price'))
                  $q->orderBy('price',request('price'));
            });
        $query = $offers->get();
        $query->each(function ($offer) use ($offers) {

            $productIds = [];
            foreach ($offer->products as $product) {
                $productIds [] = $product['product_id'];
            }
            $checkProduct = Product::query()->whereStatus(Status::Not_Active->value)
                ->whereIn('id', $productIds)->count();

            if ($checkProduct > 0) {
                array_push($this->offerIds, $offer->id);
            }
        });

        $offers = $offers->whereNotIn('id', $this->offerIds)
            ->orderByDesc('created_at')->paginate(Utility::getValByName('pagination_limit'));
        return $this->success(OfferResource::collection($offers), $offers, message: _trans('List of Offers'));
    }

    public function offerDetails($slug)
    {
        $offer = Offer::query()
            ->whereRelation('restaurant', 'country_id', '=', request('country_id'))
            ->withCount(['rates'])->with(['images','restaurant'])
            ->where(function ($q)use($slug){
                $q->whereTranslation('slug',$slug)->orWhere('id',$slug);
            })->first();
        return $this->success($offer ? OfferDetailsResource::make($offer) : null, message: _trans('Offer Details'));
    }

    public function saveDetails($slug)
    {
        $save = Save::query()
            ->whereRelation('restaurant', 'country_id', '=', request('country_id'))
            ->withCount(['rates'])->with(['images','restaurant'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->where('from', '<=', Carbon::now()->format('H:i:s'))
            ->where('to', '>=', Carbon::now()->format('H:i:s'))
            ->where(function ($q)use($slug){
                $q->whereTranslation('slug',$slug)->orWhere('id',$slug);
            })->first();
        return $this->success($save ? SaveDetailsResource::make($save) : null, message: _trans('Save Details'));
    }

    public function discountDetails($id)
    {
        $discount = Discount::query()->with(['restaurant'])
            ->whereRelation('restaurant', 'country_id', '=',request('country_id'))
            ->withCount(['rates'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->find($id);;
        return $this->success($discount ? DiscountDetailsResource::make($discount) : null, message: _trans('Discount Details'));
    }

    public function saves()
    {
        $saves = Save::query()
            ->whereRelation('restaurant', 'country_id', '=',request('country_id'))
            ->whereHas('restaurant', function ($q) {
                $q->status();
            })
            ->withCount(['rates'])->with(['images','restaurant'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->where('from', '<=', Carbon::now()->format('H:i:s'))
            ->where('to', '>=', Carbon::now()->format('H:i:s'))
            ->when('price',function ($q){
                if (request('price'))
                  $q->orderBy('price',request('price'));
            })
            ->status();
        $query = $saves->get();
        $query->each(function ($save) use ($saves) {

            $productIds = [];
            foreach ($save->products as $product) {
                $productIds [] = $product['product_id'];
            }
            $checkProduct = Product::query()->whereStatus(Status::Not_Active->value)
                ->whereIn('id', $productIds)->count();

            if ($checkProduct > 0) {
                array_push($this->saveIds, $save->id);
            }
        });

        $saves = $saves->whereNotIn('id', $this->saveIds)
            ->orderByDesc('created_at')->paginate(Utility::getValByName('pagination_limit'));
        return $this->success(SaveResource::collection($saves), $saves, message: _trans('List of Saves'));
    }

    public function discounts()
    {

        $discounts = Discount::query()->status()
            ->whereRelation('restaurant', 'country_id', '=', request('country_id'))
            ->whereHas('restaurant', function ($q) {
                $q->status();
            })->whereHas('product', function ($q) {
                $q->status();
            })
            ->with(['product.images','restaurant'])->withCount(['rates'])
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end', '>=', Carbon::now()->format('Y-m-d'))
            ->when('price',function ($q){
                if (request('price'))
                   $q->orderBy('price_after',request('price'));
            })
            ->paginate(Utility::getValByName('pagination_limit'));
        return $this->success(DiscountResource::collection($discounts), $discounts, message: _trans('List of Discounts'));
    }

    public function branchTypes()
    {
        $branchTypes = BranchType::query()->withCount(['restaurants' => function (Builder $query) {
            $query->where('country_id', '=', request('country_id'))->status();
        }])->orderByDesc('ranking')->published()->status()->get();
        return $this->success(BranchTypeResource::collection($branchTypes), message: _trans('List of Branch types'));
    }

    public function restaurants(Request $request)
    {
        $restaurants = Restaurant::query()
            ->whereCountryId($request->country_id)
            ->with(['country', 'governorate', 'region','branchTypes'=>function($q){
                $q->withCount(['restaurants'=>function($q){
                    $q->status();
                }]);

            }])
            ->when($request->branch_type_id, function ($query) use ($request) {
            return $query->whereHas('branchTypes', function ($q) use ($request) {
                $q->where('branch_type_id', $request->branch_type_id)
                    ->published();
            });
        })->withCount(['rates'])->status()->paginate(Utility::getValByName('pagination_limit'));
        return $this->success(RestaurantResource::collection($restaurants), $restaurants, message: _trans('List of Restaurant if send branch type'));
    }

    public function restaurantSearch(Request $request)
    {
        $restaurants = Restaurant::query()->whereTranslationLike('name', "%{$request->search}%", locale())
            ->whereCountryId($request->country_id)
            ->with(['country', 'governorate', 'region','branchTypes'=>function($q){
                $q->withCount(['restaurants'=>function($q){
                    $q->status();
                }]);
            }])
            ->withCount(['rates'])->status()->paginate(Utility::getValByName('pagination_limit'));
        return $this->success(RestaurantResource::collection($restaurants), $restaurants, message: _trans('List of Restaurant by search'));
    }

    public function productSearch(Request $request)
    {
        $products = Product::query()
            ->whereRelation('restaurant',  ['country_id'=>$request->country_id,'status'=>Status::Active->value]);
        if ($request->filled('key_search')){
            $products=$products->whereTranslationLike('name', "%{$request->key_search}%", locale());
        }
         if ($request->filled('colors')){
             $products=$products->whereHas('colors', function ($q) use($request){
                 $q->whereIn('code',$request->colors);
             });
         }
        if ($request->filled('sizes')) {
            $products = $products->whereHas('sizes', function ($q) use ($request) {
                $q->whereHas('translations', function ($query) use ($request) {
                    $query->where('locale', '=',locale())
                        ->whereIn('name',$request->sizes);
                });

            });
        }
        if ($request->filled('category_id')){
            $products=$products->whereIn('category_id',$request->category_id);
        }
        if ($request->filled('restaurant_id')){
            $products=$products->where('restaurant_id','=',$request->restaurant_id);
        }
        if ($request->filled('price_start')){
            $products=$products->where('price','>=',$request->price_start);
        }
        if ($request->filled('price_end')){
            $products=$products->where('price','<=',$request->price_end);
        }

        $products=$products->withCount(['rates'])->with(['category','restaurant','images', 'sizes' => function ($q) {
            $q->status();
        },'colors'=>function($q){
            $q->status();
        }])->status()->paginate(Utility::getValByName('pagination_limit'));
        return $this->success(ProductResource::collection($products), $products, message: _trans('List of products by search'));
    }


    public function contactUs(ContactUsRequest $request)
    {
        $data = $request->validated();
        $contact = ContactUs::query()->create($data);
        if ($contact) {
            $admin = Admin::query()->first();
            $notifications = Notification::query()->create([
                'type' => 'contact-us',
                'senderable_type' => ContactUs::class,
                'senderable_id' => $contact->id,
                'receiverable_type' => Admin::class,
                'receiverable_id' => $admin->id,
                'data' => [
                    'contact_us_id' => $admin->id,
                    'name' => $contact->first_name . ' ' . $contact->last_name

                ],
            ]);
            event(new GetNotificationAdmin($admin, $notifications));
            return $this->success(message: _trans('Done send contact us'));
        }
        return $this->failure(_trans('Please try again'));
    }

    public function addFavourite(FavouriteRequest $request)
    {
        try {
            $user = auth()->user();
            $data = $this->favourite($request, $user);
            if ($data) {
                if ($data == -1)
                    return $this->success(message: _trans('Remove From Favourite successfully'));
                else
                    return $this->success(message: _trans('Add To Favourite successfully'));
            }
            return $this->failure(message: _trans('Please try again'));
        } catch (\Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function addRate(RateRequest $request)
    {
        try {
            $user = auth()->user();
            $data = $this->rateRepositoryInterface->rate($request, $user);
            if ($data) {
                if ($data == -1)
                    return $this->failure(message: _trans('You Add Rate On This Item Before'));
                else
                    return $this->success(message: _trans('Add Rate successfully'));
            }
            return $this->failure(message: _trans('Please try again'));
        } catch (\Exception $exception) {
            return $this->exception($exception);
        }
    }


    public function myFavourites(Request $request)
    {
        $type = $request->input('type');
        if ($type == 'product') {
            $favourites = Favourite::where('user_id', auth()->id())
                ->whereHasMorph('favourite', Product::class,function ($q) use($request){
                    $q->whereRelation('restaurant', ['country_id'=>$request->country_id,'status'=>Status::Active->value]);
                })
                ->where('favourite_type', Product::class)
                ->with(['favourite' => function ($q) use($request) {
                    $q->whereRelation('restaurant', ['country_id'=>$request->country_id,'status'=>Status::Active->value])
                        ->withCount(['rates'])->with(['images', 'sizes' => function ($q) {
//                        $q->status();
                    }]);
                }])->paginate(Utility::getValByName('pagination_limit'));

            return $this->success(ProductFavouriteResource::collection($favourites), $favourites, message: _trans('Favourite List'));
        } elseif ($type == 'restaurant') {
            $favourites = Favourite::where('user_id', auth()->id())
                ->where('favourite_type', Restaurant::class)
                ->whereHasMorph('favourite', Restaurant::class,function ($q) use($request){
                    $q->where('country_id','=',$request->country_id)->status();
                })
                ->with(['favourite' => function ($q) {
                    $q->withCount(['rates']);
                }])
                ->paginate(Utility::getValByName('pagination_limit'));
            return $this->success(RestaurantFavouriteResource::collection($favourites), $favourites, message: _trans('Favourite List'));
        } else {
            return $this->failure(message: _trans('wrong type you must write product , furniture'));
        }

    }

    public function countries()
    {
        $countries = Country::query()->status()->orderByDesc('created_at')->get();
        return $this->success(CountryResource::collection($countries));
    }

    public function governorates(Request $request)
    {
        $governorate = Governorate::query()->whereCountryId($request->country_id)
            ->status()->orderByDesc('created_at')->get();
        return $this->success(GovernorateResource::collection($governorate));
    }

    public function regions(Request $request)
    {
        $regions = Region::query()
            ->whereCountryId($request->country_id)
            ->whereGovernorateId($request->governorate_id)
            ->status()->orderByDesc('created_at')->get();
        return $this->success(RegionResource::collection($regions));
    }

    public function pages(Request $request)
    {
        $page = Page::query()->wherePageType($request->page_name)->status()->first();
        if ($page) {
            return $this->success(PageResource::make($page), message: _trans('Details page' . ' ' . $request->page_name));
        }
        return $this->failure(_trans('Not found this page' . ' ' . $request->page_name));
    }

    public function socialMedia()
    {
        $socialMedia = SocialMedia::query()->status()->get();
        return $this->success(SocialMediaResource::collection($socialMedia), message: _trans('Social Media list'));
    }

    public function setting()
    {
        $setting = Utility::settings();
        $data = array_merge($setting, [
            "icon" => getAvatar($setting['icon']),
            "web_logo" => getAvatar($setting['web_logo']),
            "mobile_logo" => getAvatar($setting['mobile_logo']),
            "favicon" => getAvatar($setting['favicon']),
            "qr_link_google_play" => getAvatar('qrCode/link_google_play.png'),
            "qr_link_apple_store" => getAvatar('qrCode/link_apple_store.png'),
            "qr_link_website" => getAvatar('qrCode/link_website.png'),
        ]);
        //unset($data['icon'], $data['web_logo'], $data['web_logo'], $data['mobile_logo'], $data['favicon']);
        //unset($data['link_google_play'], $data['link_apple_store'], $data['link_website']);
        unset($data['google_maps_api'], $data['FCM_SERVER_KEY'], $data['FCM_SERVER_URL']);
        return $this->success($data, message: _trans('Setting site list'));
    }

    public function getCountCartOrFavorite(Request $request)
    {
        $customer = $request->user()->userable;
        $cart = Cart::query()
            ->whereRelation('restaurant', ['country_id'=>$request->country_id,'status'=>Status::Active->value] )
            ->whereCustomerId($customer->id);
        $cartIds = $cart->pluck('id')->toArray();
        $cartDetails = CartDetail::query()->whereIn('cart_id', $cartIds)->count();
        $favorite = Favourite::query()->whereUserId($request->user()->id)->count();
        return $this->success([
            'cart_count' => $cart->count(),
            'cart_details_count' => $cartDetails,
            'favorite_count' => $favorite,
        ], message: _trans('Get Count cart and favorite'));
    }

    public function governorateRegionCategory()
    {
        $categories = Category::query()->whereHas('products',function ($q){
            $q->whereRelation('restaurant', ['country_id'=>request('country_id'),'status'=>Status::Active->value]);
        })
            ->withCount(['products' => function (Builder $query) {
                $query->whereRelation('restaurant',  ['country_id'=>request('country_id'),'status'=>Status::Active->value]);
            }])->orderByDesc('products_count')->limit(10)->get();

        $governorates = Governorate::query()
            ->whereCountryId(request('country_id'))
            ->has('restaurants')->withCount(['restaurants' => function (Builder $query) {
                $query->where('country_id', '=', request('country_id'))->status();
            }])->orderByDesc('restaurants_count')->limit(10)->get();

        $regions = Region::query()->whereCountryId(request('country_id'))
            ->has('restaurants')
            ->withCount(['restaurants' => function (Builder $query) {
                $query->where('country_id', '=',request('country_id'))->status();
            }])
            ->orderByDesc('restaurants_count')
            ->limit(10)
            ->get();
        return $this->success([
            'categories' => CategoryResource::collection($categories),
            'governorates' => GovernorateResource::collection($governorates),
            'regions' => RegionResource::collection($regions),
        ], message: _trans('List categories and governorates and regions in footer'));
    }

    public function productByCategory($slug)
    {
        $category = Category::query()
            ->whereTranslation('slug',$slug)->orWhere('id',$slug)->first();
        if ($category)
        {
            $products = Product::query()
                ->whereRelation('restaurant',  ['country_id'=>request('country_id'),'status'=>Status::Active->value])
                ->whereCategoryId($category->id)
                ->withCount(['rates'])->with(['restaurant', 'images', 'sizes' => function ($q) {
                    $q->status();
                }, 'colors' => function ($q) {
                    $q->status();
                }])->when('price', function ($q) {
                    if (request('price'))
                        $q->orderBy('price', request('price'));
                })->status()->paginate(Utility::getValByName('pagination_limit'));
            return $this->success([
                'category' => $category ? CategoryResource::make($category) : null,
                'products' => ProductResource::collection($products)
            ], $products, message: _trans('List of products by category'));
        }
        return $this->failure(message: _trans('category not found'));

    }

    public function restaurantByGovernorate($slug)
    {
        $governorate = Governorate::query()
            ->whereCountryId(request('country_id'))
            ->where(function ($q)use($slug){
                $q->whereTranslation('slug',$slug)->orWhere('id',$slug);
            })->first();
        $restaurant = Restaurant::query()
            ->whereCountryId(request('country_id'))
            ->with(['country', 'governorate', 'region','branchTypes'=>function($q){
                $q->withCount(['restaurants']);

            }])
            ->whereGovernorateId($governorate->id)
            ->withCount(['rates'])->status()->paginate(Utility::getValByName('pagination_limit'));
        return $this->success([
            'governorate' => $governorate ? GovernorateResource::make($governorate) : null,
            'restaurants' => RestaurantResource::collection($restaurant)
        ], $restaurant, message: _trans('List of Restaurants governorate'));
    }

    public function restaurantByRegion($slug)
    {
        $region = Region::query()
            ->whereCountryId(request('country_id'))
            ->where(function ($q)use($slug){
                $q->whereTranslation('slug',$slug)->orWhere('id',$slug);
            })->first();
        $restaurant = Restaurant::query()->with(['country', 'governorate', 'region','branchTypes'=>function($q){
            $q->withCount(['restaurants']);
        }])
            ->whereCountryId(request('country_id'))
            ->whereRegionId($region->id)
            ->withCount(['rates'])->status()->paginate(Utility::getValByName('pagination_limit'));
        return $this->success([
            'region' => $region ? RegionResource::make($region) : null,
            'restaurants' => RestaurantResource::collection($restaurant)
        ], $restaurant, message: _trans('List of Restaurants region'));
    }

    public function mostWantedProducts()
    {
        $products = Product::query()
            ->whereRelation('restaurant',  ['country_id'=>request('country_id'),'status'=>Status::Active->value])
            ->has('orderDetails')->withCount(['orderDetails'])
            ->with(['orderDetails','restaurant'])
            ->orderByDesc('order_details_count')
            ->status()->paginate(Utility::getValByName('pagination_limit'));
        return $this->success(ProductResource::collection($products), $products, message: _trans('List of most wanted products'));
    }

    public function joinUs(JoinUsRequest $request)
    {
        try {
            $data = $request->validated();
            $joinUs = JoinUs::query()->create($data);
            $joinUs->branchTypes()->attach($request->branch_type_id);
            $admin = Admin::query()->first();
            $notifications = Notification::query()->create([
                'type' => 'join_us',
                'senderable_type' => JoinUs::class,
                'senderable_id' => $joinUs->id,
                'receiverable_type' => Admin::class,
                'receiverable_id' => $admin->id,
                'data' => [
                    'join_us_id' => $admin->id,
                    'owner_name' => $joinUs->owner_name,
                    'owner_avatar' => asset('assets/images/no-image.png'),
                ],
            ]);
            event(new GetNotificationAdmin($admin, $notifications));
            return $this->success(message: _trans('Done send request successfully'));
        } catch (\Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function nearby(AuthRequest $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $radius = Utility::getValByName('radius');
        $restaurants = Restaurant::query()->status()
            ->whereCountryId(request('country_id'))
            ->with(['country', 'governorate', 'region','branchTypes'=>function($q){
                $q->withCount(['restaurants'=>function($q){
                    $q->status();
                }]);
            }])
            ->select('*')
            ->selectRaw("( 6371000 * acos( cos( radians(?) )
            * cos( radians( restaurants.lat ) )
            * cos( radians( restaurants.lng ) - radians(?)) + sin( radians(?) )
            * sin( radians( restaurants.lat ) ) )) AS distance", [$lat, $lng, $lat])
            ->having("distance", "<", $radius)
            ->orderBy("distance")
            ->paginate(Utility::getValByName('pagination_limit'));

        return $this->success(RestaurantResource::collection($restaurants), $restaurants, message: _trans('list of Restaurants nearby'));
    }

    public function deliveryArea($restaurant_id)
    {
        $delivery = DeliveryArea::query()
            ->whereRelation('restaurant',  ['country_id'=>request('country_id'),'status'=>Status::Active->value])
            ->whereCountryId(request('country_id'))
            ->with(['areas'])->whereRestaurantId($restaurant_id)->get();
        return $this->success(DeliveryAreaResource::collection($delivery));
    }

    public function governorateAreas($delivery_area_id)
    {
        $governorates = Governorate::query()
            ->whereCountryId(request('country_id'))
            ->with(['areas'])->whereHas('areas',function ($q) use($delivery_area_id){
            $q->where('delivery_area_id',$delivery_area_id);
        })->get();
        return $this->success(GovernorateResource::collection($governorates));
    }

    public function areaRegions($governorate_id,$delivery_area_id)
    {
        $areas = Region::query()
            ->whereCountryId(request('country_id'))
            ->with(['areas'])->whereHas('areas',function ($q) use($delivery_area_id){
            $q->where('delivery_area_id',$delivery_area_id);
        })->whereGovernorateId($governorate_id)->get();
        return $this->success(RegionResource::collection($areas));
    }
    public function searchRestaurants(Request $request)
    {
        try {
            $restaurants = Restaurant::query()
                ->whereCountryId(request('country_id'))
                ->with(['country', 'governorate', 'region','branchTypes'=>function($q){
                    $q->withCount(['restaurants'=>function($q){
                        $q->status();
                    }]);
                }])
                ->withCount(['rates'])->status();

            $data = [];
            if ($request->filled('branch_type_id')) {
                $branch_type = BranchType::query()
                    ->find($request->branch_type_id);
                if ($branch_type) {
                    $restaurants = $restaurants->whereHas('branchTypes', function ($q) use ($request) {
                        $q->where('branch_type_id', $request->branch_type_id)
                            ->status();
                    });
                    $data += [
                        'branch_type' => $branch_type ? BranchTypeResource::make($branch_type) : null,
                    ];
                }
            }
            if ($request->filled('governorate_id')) {
                $governorate = Governorate::query()
                    ->whereCountryId(request('country_id'))
                    ->find($request->governorate_id);
                if ($governorate) {
                    $restaurants = $restaurants->whereGovernorateId($governorate->id);
                    $data += [
                        'governorate' => $governorate ? GovernorateResource::make($governorate) : null,
                    ];
                }
            }

            if ($request->filled('region_id')) {
                $region = Region::query()->whereCountryId(request('country_id'))
                    ->find($request->region_id);
                if ($region) {
                    $restaurants = $restaurants->whereRegionId($region->id);
                    $data += [
                        'region' => $region ? RegionResource::make($region) : null,
                    ];
                }
            }
            if ($request->filled('lat') && $request->filled('lng')) {
                $lat = $request->lat;
                $lng = $request->lng;
                $radius = Utility::getValByName('radius');
                $restaurants = $restaurants->select('*')->selectRaw("( 6371000 * acos( cos( radians(?) )
                                * cos( radians( restaurants.lat ) )
                                * cos( radians( restaurants.lng ) - radians(?)) + sin( radians(?) )
                                * sin( radians( restaurants.lat ) ) )) AS distance", [$lat, $lng, $lat])
                    //->having("distance", "<", $radius)
                    ->orderBy("distance");
                $data += [
                    'lat' => $lat,
                    'lng' => $lng,
                    'radius' => $radius,
                ];

            }
            if ($request->filled('key_search')) {
                $lang = $request->header('Accept-Language', default_lang());
                $restaurants = $restaurants->where(function ($q)use($request,$lang){
                    $q->whereTranslationLike('name', "%{$request->key_search}%", $lang)
                        ->orWhereTranslationLike('description', "%{$request->key_search}%", $lang);
                });
                $data += [
                    'key_search' => $request->key_search,
                    'Accept_Language' => $request->header('Accept-Language'),
                    //'default_lang' => default_lang(),
                ];
            }

            $restaurants = $restaurants->paginate(Utility::getValByName('pagination_limit'));
            $data += [
                'restaurants' => RestaurantResource::collection($restaurants)
            ];
            return $this->success($data, $restaurants, message: _trans('list of restaurants search'));

        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function searchAll(Request $request)
    {
        try {
            $restaurants = Restaurant::query()
                ->whereCountryId(request('country_id'))
                ->with(['country', 'governorate', 'region','branchTypes'=>function($q){
                    $q->withCount(['restaurants'=>function($q){
                        $q->status();
                    }]);
                }])->withCount(['rates'])->status();
            $products = Product::query()
                ->whereRelation('restaurant',  ['country_id'=>request('country_id'),'status'=>Status::Active->value])
                ->withCount(['rates'])->with(['restaurant','images', 'sizes' => function ($q) {
                $q->status();
            },'colors'=>function($q){
                $q->status();
            }])->status();
            $data = [];
            if ($request->filled('governorate_id')) {
                $governorate = Governorate::query()->find($request->governorate_id);
                if ($governorate) {
                    $restaurants = $restaurants->whereGovernorateId($governorate->id);
                    $products=$products->whereHas('restaurant',function ($q)use($governorate){
                        $q->whereGovernorateId($governorate->id);
                    });
                    $data += [
                        'governorate' => $governorate ? GovernorateResource::make($governorate) : null,
                    ];
                }
            }

            if ($request->filled('region_id')) {
                $region = Region::query()->find($request->region_id);
                if ($region) {
                    $restaurants = $restaurants->whereRegionId($region->id);
                    $products=$products->whereHas('restaurant',function ($q)use($region){
                        $q->whereRegionId($region->id);
                    });
                    $data += [
                        'region' => $region ? RegionResource::make($region) : null,
                    ];
                }
            }
            if ($request->filled('lat') && $request->filled('lng')) {
                $lat = $request->lat;
                $lng = $request->lng;
                $radius = Utility::getValByName('radius');
                $restaurants = $restaurants->select('*')->selectRaw("( 6371000 * acos( cos( radians(?) )
                                * cos( radians( restaurants.lat ) )
                                * cos( radians( restaurants.lng ) - radians(?)) + sin( radians(?) )
                                * sin( radians( restaurants.lat ) ) )) AS distance", [$lat, $lng, $lat])
                    ->orderBy("distance");

                $products=$products->whereHas('restaurant',function ($q)use($lat,$lng){
                    $q->select('*')->selectRaw("( 6371000 * acos( cos( radians(?) )
                                * cos( radians( restaurants.lat ) )
                                * cos( radians( restaurants.lng ) - radians(?)) + sin( radians(?) )
                                * sin( radians( restaurants.lat ) ) )) AS distance", [$lat, $lng, $lat])
                        ->orderBy("distance");
                });
                $data += [
                    'lat' => $lat,
                    'lng' => $lng,
                    'radius' => $radius,
                ];


            }
            if ($request->filled('key_search')) {
                $lang = $request->header('Accept-Language', default_lang());
                $restaurants = $restaurants->where(function (Builder $query) use ($request) {
                    $query->whereHas('translations', function ($query) use ($request) {
                        $query->where('locale', '=', $request->header('Accept-Language'))
                            ->where('name', 'LIKE', "%{$request->key_search}%")
                            ->orWhere('description', 'LIKE', "%{$request->key_search}");
                    });
                });
                $products = $products->whereTranslationLike('name', "%{$request->key_search}%", $lang);
                $data += [
                    'key_search' => $request->key_search,
                    'Accept_Language' => $request->header('Accept-Language'),
                ];
            }

            $restaurants = $restaurants->paginate(Utility::getValByName('pagination_limit'));
            $products = $products->paginate(Utility::getValByName('pagination_limit'));
            $data += [
                'restaurants' => RestaurantResource::collection($restaurants),
                'restaurants_paginator' => $this->paginate($restaurants),
                'products' => ProductResource::collection($products),
                'products_paginator' => $this->paginate($products),
            ];
            return $this->success($data, message: _trans('list of search'));

        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function getShareLink(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'type' => ['required', Rule::in(['product', 'restaurant','offer','discount','save','blog'])],
            'redirect_url' => 'required',
        ]);

        return $this->success(route('link-preview',
            [
                'type' => $request->type,
                'redirect' => str_replace('{item_id}', $request->item_id, $request->redirect_url),
                'item_id' => $request->item_id,
                'lang'=>locale()
            ]
        ));

    }
    public function rates(Request $request)
    {
        try {
            $model_type = match ($request->model_type) {
                'restaurant' => Restaurant::class,
                'offer' => Offer::class,
                'save' => Save::class,
                'product' => Product::class,
                default => '',
            };
            $rates = Rate::query()->where([
                'rate_type' => $model_type,
                'rate_id' => $request->model_id,
                'user_id' => auth()->id(),
            ])->first();
            return $this->success($rates ? RateResource::make($rates) : null);
        } catch (\Exception $exception) {
            return $this->exception($exception);
        }
    }
}
