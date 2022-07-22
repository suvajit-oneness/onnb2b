<?php

namespace App\Providers;

use App\Interfaces\CategoryInterface;
use App\Interfaces\AdminInterface;
use App\Interfaces\SubcategoryInterface;
use App\Interfaces\CollectionInterface;
use App\Interfaces\CouponInterface;
use App\Interfaces\OfferInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\AddressInterface;
use App\Interfaces\FaqInterface;
use App\Interfaces\SettingsInterface;
use App\Interfaces\StoreInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\TransactionInterface;
use App\Interfaces\NotificationInterface;
use App\Interfaces\CartInterface;
use App\Interfaces\SearchInterface;
use App\Interfaces\CheckoutInterface;
use App\Interfaces\InvoiceInterface;
use App\Interfaces\TargetInterface;
use App\Repositories\TargetRepository;
use App\Interfaces\DistributorInterface;
use App\Repositories\DistributorRepository;
use App\Interfaces\CatalogueInterface;
use App\Repositories\CatalogueRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\CollectionRepository;
use App\Repositories\CouponRepository;
use App\Repositories\OfferRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\AddressRepository;
use App\Repositories\FaqRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\StoreRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\CartRepository;
use App\Repositories\AdminRepository;
use App\Repositories\SearchRepository;
use App\Repositories\CheckoutRepository;
use App\Repositories\InvoiceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(SubcategoryInterface::class, SubcategoryRepository::class);
        $this->app->bind(CollectionInterface::class, CollectionRepository::class);
        $this->app->bind(CouponInterface::class, CouponRepository::class);
        $this->app->bind(OfferInterface::class, OfferRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(AddressInterface::class, AddressRepository::class);
        $this->app->bind(FaqInterface::class, FaqRepository::class);
        $this->app->bind(SettingsInterface::class, SettingsRepository::class);
        $this->app->bind(StoreInterface::class, StoreRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(TransactionInterface::class, TransactionRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationRepository::class);
        $this->app->bind(CartInterface::class, CartRepository::class);
        $this->app->bind(SearchInterface::class, SearchRepository::class);
        $this->app->bind(AdminInterface::class, AdminRepository::class);
        $this->app->bind(CheckoutInterface::class, CheckoutRepository::class);
        $this->app->bind(TargetInterface::class, TargetRepository::class);
        $this->app->bind(InvoiceInterface::class, InvoiceRepository::class);
        $this->app->bind(DistributorInterface::class, DistributorRepository::class);
        $this->app->bind(CatalogueInterface::class, CatalogueRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
