<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_items')->insert([
          [
            'id' =>1,
            'title' => 'Dashboard',
            'menu_id' =>1,
            'icon_class' =>'fa-tachometer-alt',
            'parent_id' => null,
            'order' => 1,
            'key'=>null,
            'route' => 'dashboard',
            'model_name' => null
          ],[
            'id' =>2,
            'title' => 'Companies',
            'menu_id' =>1,
            'icon_class' =>'fa-map-alt',
            'parent_id' => null,
            'order' => 2,
            'key'=>null,
            'route' => 'admin.companies.index',
            'model_name' => null
          ],[
            'id' =>3,
            'title' => 'Users',
            'menu_id' =>1,
            'icon_class' =>'fa-users-alt',
            'parent_id' => null,
            'order' => 3,
            'key'=>null,
            'route' => 'admin.companies.users',
            'model_name' => null
          ],[
            'id' =>4,
            'title' => 'Modules Permissions',
            'menu_id' =>1,
            'icon_class' =>'fa-cog-alt',
            'parent_id' => null,
            'order' => 4,
            'key'=>null,
            'route' => 'admin.modules.index',
            'model_name' => null
          ],[
            'id' =>5,
            'title' => 'Dashboard',
            'menu_id' =>2,
            'icon_class' =>'fa-tachometer-alt',
            'parent_id' => null,
            'order' => 1,
            'key'=>null,
            'route' => 'users.index',
            'model_name' => null
          ],[
            'id' =>6,
            'title' => 'Sales',
            'menu_id' =>2,
            'icon_class' =>'fa-balance-scale',
            'parent_id' => null,
            'order' => 2,
            'key'=>null,
            'route' => 'seller.index',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>7,
            'title' => 'Sales Requests',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 6,
            'order' => 1,
            'key'=>'browse_sells',
            'route' => 'seller.index',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>8,
            'title' => 'Manage Sales',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 6,
            'order' => 2,
            'key'=>'browse_sells',
            'route' => 'seller.manage_sales',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>9,
            'title' => 'Customer',
            'menu_id' =>2,
            'icon_class' =>'fa-handshake',
            'parent_id' => null,
            'order' => 3,
            'key'=>null,
            'route' => 'customer.index',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>10,
            'title' => 'Manage Customer',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 9,
            'order' => 1,
            'key'=>null,
            'route' => 'customer.index',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>11,
            'title' => 'Customer Ledger',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 9,
            'order' => 2,
            'key'=>'browse_customerledger',
            'route' => 'customer.customer_ledger',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>12,
            'title' => 'Credit Customer',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 9,
            'order' => 3,
            'key'=>'browse_creditcustomer',
            'route' => 'customer.credit_customer',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>13,
            'title' => 'Paid Customer',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 9,
            'order' => 4,
            'key'=>'browse_paidcustomer',
            'route' => 'customer.paid_customer',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>14,
            'title' => 'Customer Advance',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 9,
            'order' => 5,
            'key'=>'browse_advancecustomer',
            'route' => 'customer.customer_advance',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>15,
            'title' => 'Product',
            'menu_id' =>2,
            'icon_class' =>'fa-shopping-bag',
            'parent_id' => null,
            'order' => 4,
            'key'=>null,
            'route' => 'product.index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],[
            'id' =>16,
            'title' => 'Unit',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 15,
            'order' => 1,
            'key'=>'browse_unit',
            'route' => 'product.unit_index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],[
            'id' =>17,
            'title' => 'Category',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 15,
            'order' => 2,
            'key'=>'browse_category',
            'route' => 'product.category_index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],[
            'id' =>18,
            'title' => 'Manage Product',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 15,
            'order' => 3,
            'key'=>null,
            'route' => 'product.index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],[
            'id' =>161,
            'title' => 'Product Reports',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 15,
            'order' => 4,
            'key'=>null,
            'route' => 'product.reports',
            'model_name' => 'Modules\Product\Entities\Product'
          ]
          ,[
            'id' =>19,
            'title' => 'Warehouse',
            'menu_id' =>2,
            'icon_class' =>'fa-shopping-cart',
            'parent_id' => null,
            'order' => 5,
            'key'=>null,
            'route' => 'warehouse.index',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>20,
            'title' => 'Warehouse Products',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 19,
            'order' => 1,
            'key'=>'browse_wareproducts',
            'route' => 'warehouse.products',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>21,
            'title' => 'Warehouse List',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 19,
            'order' => 2,
            'key'=>null,
            'route' => 'warehouse.index',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>22,
            'title' => 'Product Requests',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 19,
            'order' => 3,
            'key'=>null,
            'route' => 'warehouse.prod_requests',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>23,
            'title' => 'Supplier',
            'menu_id' =>2,
            'icon_class' =>'fa-user',
            'parent_id' => null,
            'order' => 6,
            'key'=>null,
            'route' => 'supplier.index',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],[
            'id' =>24,
            'title' => 'Manage Supplier',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 23,
            'order' => 1,
            'key'=>'browse_supplier',
            'route' => 'supplier.index',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],[
            'id' =>25,
            'title' => 'Supplier Ledger',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 23,
            'order' => 2,
            'key'=>null,
            'route' => 'supplier.ledgers',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],
          [
            'id' =>26,
            'title' => 'Supplier Products Report',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 23,
            'order' => 3,
            'key'=>null,
            'route' => 'supplier.products',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],
          [
            'id' =>27,
            'title' => 'Stock',
            'menu_id' =>2,
            'icon_class' =>'fa-chart-bar',
            'parent_id' => null,
            'order' => 7,
            'key'=>null,
            'route' => 'warehouse.stock',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>28,
            'title' => 'Stock Report Summary',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 27,
            'order' => 1,
            'key'=>null,
            'route' => 'warehouse.stock',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>159,
            'title' => 'Stock Report Details',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 27,
            'order' => 2,
            'key'=>null,
            'route' => 'warehouse.stock_details',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>164,
            'title' => 'Stock Movement Report',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 27,
            'order' => 3,
            'key'=>null,
            'route' => 'warehouse.stock_movement',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],
          [
            'id' =>29,
            'title' => 'Return',
            'menu_id' =>2,
            'icon_class' =>'fa-retweet',
            'parent_id' => null,
            'order' => 8,
            'key'=>null,
            'route' => 'warehouse.return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>30,
            'title' => 'Manage Return',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 29,
            'order' => 1,
            'key'=>null,
            'route' => 'warehouse.return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>31,
            'title' => 'Stock Return List',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 29,
            'order' => 2,
            'key'=>null,
            'route' => 'warehouse.stock_return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>32,
            'title' => 'Supplier Return List',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 29,
            'order' => 3,
            'key'=>null,
            'route' => 'warehouse.supplier_return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>33,
            'title' => 'Wastage Return List',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 29,
            'order' => 4,
            'key'=>null,
            'route' => 'warehouse.wastage_return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>34,
            'title' => 'Accounts',
            'menu_id' =>2,
            'icon_class' =>'fa-sitemap',
            'parent_id' => null,
            'order' => 10,
            'key'=>null,
            'route' => 'accounts.index',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>35,
            'title' => 'Chart Of Accounts',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 1,
            'key'=>'browse_accounts',
            'route' => 'accounts.index',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>36,
            'title' => 'Customer Receive',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 2,
            'key'=>'browse_customerreceive',
            'route' => 'accounts.customer_receive',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>37,
            'title' => 'Cash Adjusment',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 3,
            'key'=>'browse_cashadjusment',
            'route' => 'accounts.cash_adjusment',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>38,
            'title' => 'Debit Voucher',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 4,
            'key'=>'browse_devidvoucher',
            'route' => 'accounts.debit_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>39,
            'title' => 'Credit Voucher',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 5,
            'key'=>'browse_creditvoucher',
            'route' => 'accounts.credit_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>40,
            'title' => 'Contra Voucher',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 6,
            'key'=>'browse_contravoucher',
            'route' => 'accounts.contra_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>41,
            'title' => 'Journal Voucher',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 7,
            'key'=>'browse_journalvoucher',
            'route' => 'accounts.journal_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>42,
            'title' => 'Voucher Approval',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 9,
            'key'=>'browse_voucherapproval',
            'route' => 'accounts.voucher_approve',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>43,
            'title' => 'Cash Book',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 1,
            'key'=>'browse_cashbook',
            'route' => 'accounts.cash_book',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>44,
            'title' => 'Inventory Ledger',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 2,
            'key'=>'browse_inventoryledger',
            'route' => 'accounts.inventory_ledger',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>45,
            'title' => 'Bank Book',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 3,
            'key'=>'browse_bankbook',
            'route' => 'accounts.bank_book',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>46,
            'title' => 'General Ledger',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 4,
            'key'=>'browse_generalledger',
            'route' => 'accounts.general_ledger',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>47,
            'title' => 'Trial Balance',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 5,
            'key'=>'browse_trialbalance',
            'route' => 'accounts.trial_balance',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>48,
            'title' => 'Profit Loss',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 7,
            'key'=>'browse_profitloss',
            'route' => 'accounts.profit_loss',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>49,
            'title' => 'Cash Flow',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 8,
            'key'=>'browse_cashflow',
            'route' => 'accounts.cash_flow',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>50,
            'title' => 'COA Print',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 9,
            'key'=>'browse_coaprint',
            'route' => 'accounts.coa_print',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>51,
            'title' => 'Bank',
            'menu_id' =>2,
            'icon_class' =>'fa-university',
            'parent_id' => null,
            'order' => 11,
            'key'=>null,
            'route' => 'bank.index',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],
          [
            'id' =>52,
            'title' => 'Manage Bank',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 51,
            'order' => 1,
            'key'=>'browse_bank',
            'route' => 'bank.index',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],
          [
            'id' =>53,
            'title' => 'Bank Transaction',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 51,
            'order' => 2,
            'key'=>'browse_transaction',
            'route' => 'bank.transactions',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],
          [
            'id' =>54,
            'title' => 'Bank Ledger',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 51,
            'order' => 3,
            'key'=>'browse_ledger',
            'route' => 'bank.ledgers',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],[
            'id' =>55,
            'title' => 'Company Settings',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => null,
            'order' => 15,
            'key'=>null,
            'route' => 'roles.index',
            'model_name' => 'Modules\Role\Entities\Role'
          ],[
            'id' =>56,
            'title' => 'Roles',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 55,
            'order' => 1,
            'key'=>null,
            'route' => 'roles.index',
            'model_name' => 'Modules\Role\Entities\Role'
          ],[
            'id' =>57,
            'title' => 'Dashboard',
            'menu_id' =>3,
            'icon_class' =>'fa-tachometer-alt',
            'parent_id' => null,
            'order' => 1,
            'key'=>null,
            'route' => 'users.index',
            'model_name' => null
          ],[
            'id' =>58,
            'title' => 'Sales',
            'menu_id' =>3,
            'icon_class' =>'fa-balance-scale',
            'parent_id' => null,
            'order' => 2,
            'key'=>null,
            'route' => 'seller.index',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>59,
            'title' => 'Sales Requisition',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 58,
            'order' => 1,
            'key'=>null,
            'route' => 'seller.index',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>60,
            'title' => 'Manage Sales',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 58,
            'order' => 2,
            'key'=>null,
            'route' => 'seller.manage_sales',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>61,
            'title' => 'Customer',
            'menu_id' =>3,
            'icon_class' =>'fa-handshake',
            'parent_id' => null,
            'order' => 3,
            'key'=>null,
            'route' => 'customer.index',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>62,
            'title' => 'Manage Customer',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 61,
            'order' => 1,
            'key'=>null,
            'route' => 'customer.index',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>63,
            'title' => 'Customer Ledger',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 61,
            'order' => 2,
            'key'=>'browse_customerledger',
            'route' => 'customer.customer_ledger',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>64,
            'title' => 'Credit Customer',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 61,
            'order' => 3,
            'key'=>'browse_creditcustomer',
            'route' => 'customer.credit_customer',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>65,
            'title' => 'Paid Customer',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 61,
            'order' => 4,
            'key'=>'browse_paidcustomer',
            'route' => 'customer.paid_customer',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>66,
            'title' => 'Customer Advance',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 61,
            'order' => 5,
            'key'=>'browse_advancecustomer',
            'route' => 'customer.customer_advance',
            'model_name' => 'Modules\Customer\Entities\Customer'
          ],[
            'id' =>67,
            'title' => 'Product',
            'menu_id' =>3,
            'icon_class' =>'fa-shopping-bag',
            'parent_id' => null,
            'order' => 4,
            'key'=>null,
            'route' => 'product.index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],[
            'id' =>68,
            'title' => 'Unit',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 67,
            'order' => 1,
            'key'=>'browse_unit',
            'route' => 'product.unit_index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],[
            'id' =>69,
            'title' => 'Category',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 67,
            'order' => 2,
            'key'=>'browse_category',
            'route' => 'product.category_index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],[
            'id' =>70,
            'title' => 'Manage Product',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 67,
            'order' => 3,
            'key'=>null,
            'route' => 'product.index',
            'model_name' => 'Modules\Product\Entities\Product'
          ],
          [
            'id' =>162,
            'title' => 'Product Reports',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 67,
            'order' => 4,
            'key'=>null,
            'route' => 'product.reports',
            'model_name' => 'Modules\Product\Entities\Product'
          ],
          [
            'id' =>71,
            'title' => 'Warehouse',
            'menu_id' =>3,
            'icon_class' =>'fa-shopping-cart',
            'parent_id' => null,
            'order' => 5,
            'key'=>null,
            'route' => 'warehouse.index',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>72,
            'title' => 'Warehouse Products',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 71,
            'order' => 1,
            'key'=>'browse_wareproducts',
            'route' => 'warehouse.products',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>73,
            'title' => 'Warehouse List',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 71,
            'order' => 2,
            'key'=>null,
            'route' => 'warehouse.index',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>74,
            'title' => 'Product Requests',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 71,
            'order' => 3,
            'key'=>null,
            'route' => 'warehouse.prod_requests',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],
          [
            'id' =>75,
            'title' => 'Supplier',
            'menu_id' =>3,
            'icon_class' =>'fa-user',
            'parent_id' => null,
            'order' => 6,
            'key'=>null,
            'route' => 'supplier.index',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],[
            'id' =>76,
            'title' => 'Manage Supplier',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 75,
            'order' => 1,
            'key'=>'browse_supplier',
            'route' => 'supplier.index',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],[
            'id' =>77,
            'title' => 'Supplier Ledger',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 75,
            'order' => 2,
            'key'=>null,
            'route' => 'supplier.ledgers',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],
          [
            'id' =>78,
            'title' => 'Supplier Products Report',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 75,
            'order' => 3,
            'key'=>null,
            'route' => 'supplier.products',
            'model_name' => 'Modules\Supplier\Entities\Supplier'
          ],
          [
            'id' =>79,
            'title' => 'Stock',
            'menu_id' =>3,
            'icon_class' =>'fa-chart-bar',
            'parent_id' => null,
            'order' => 7,
            'key'=>null,
            'route' => 'warehouse.stock',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>80,
            'title' => 'Stock Report Summary',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 79,
            'order' => 1,
            'key'=>null,
            'route' => 'warehouse.stock',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>160,
            'title' => 'Stock Report Details',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 79,
            'order' => 2,
            'key'=>null,
            'route' => 'warehouse.stock_details',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],
          [
            'id' =>81,
            'title' => 'Return',
            'menu_id' =>3,
            'icon_class' =>'fa-retweet',
            'parent_id' => null,
            'order' => 8,
            'key'=>null,
            'route' => 'warehouse.return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>82,
            'title' => 'Manage Return',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 81,
            'order' => 1,
            'key'=>null,
            'route' => 'warehouse.return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>83,
            'title' => 'Stock Return List',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 82,
            'order' => 2,
            'key'=>null,
            'route' => 'warehouse.stock_return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>84,
            'title' => 'Supplier Return List',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 82,
            'order' => 3,
            'key'=>null,
            'route' => 'warehouse.supplier_return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>85,
            'title' => 'Wastage Return List',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 82,
            'order' => 4,
            'key'=>null,
            'route' => 'warehouse.wastage_return',
            'model_name' => 'Modules\Warehouse\Entities\Warehouse'
          ],[
            'id' =>86,
            'title' => 'Accounts',
            'menu_id' =>3,
            'icon_class' =>'fa-sitemap',
            'parent_id' => null,
            'order' => 10,
            'key'=>null,
            'route' => 'accounts.index',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>87,
            'title' => 'Chart Of Accounts',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 1,
            'key'=>'browse_accounts',
            'route' => 'accounts.index',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>88,
            'title' => 'Customer Receive',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 2,
            'key'=>'browse_customerreceive',
            'route' => 'accounts.customer_receive',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>89,
            'title' => 'Cash Adjusment',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 3,
            'key'=>'browse_cashadjusment',
            'route' => 'accounts.cash_adjusment',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>90,
            'title' => 'Debit Voucher',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 4,
            'key'=>'browse_devidvoucher',
            'route' => 'accounts.debit_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>91,
            'title' => 'Credit Voucher',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 5,
            'key'=>'browse_creditvoucher',
            'route' => 'accounts.credit_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>92,
            'title' => 'Contra Voucher',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 6,
            'key'=>'browse_contravoucher',
            'route' => 'accounts.contra_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>93,
            'title' => 'Journal Voucher',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 7,
            'key'=>'browse_journalvoucher',
            'route' => 'accounts.journal_voucher',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>94,
            'title' => 'Voucher Approval',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 9,
            'key'=>'browse_voucherapproval',
            'route' => 'accounts.voucher_approve',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>95,
            'title' => 'Cash Book',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 10,
            'key'=>'browse_cashbook',
            'route' => 'accounts.cash_book',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>96,
            'title' => 'Inventory Ledger',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 11,
            'key'=>'browse_inventoryledger',
            'route' => 'accounts.inventory_ledger',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>97,
            'title' => 'Bank Book',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 12,
            'key'=>'browse_bankbook',
            'route' => 'accounts.bank_book',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>98,
            'title' => 'General Ledger',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 13,
            'key'=>'browse_generalledger',
            'route' => 'accounts.general_ledger',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>99,
            'title' => 'Trial Balance',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 14,
            'key'=>'browse_trialbalance',
            'route' => 'accounts.trial_balance',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>100,
            'title' => 'Profit Loss',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 15,
            'key'=>'browse_profitloss',
            'route' => 'accounts.profit_loss',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>101,
            'title' => 'Cash Flow',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 16,
            'key'=>'browse_cashflow',
            'route' => 'accounts.cash_flow',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>102,
            'title' => 'COA Print',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 17,
            'key'=>'browse_coaprint',
            'route' => 'accounts.coa_print',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>103,
            'title' => 'Bank',
            'menu_id' =>3,
            'icon_class' =>'fa-university',
            'parent_id' => null,
            'order' => 11,
            'key'=>null,
            'route' => 'bank.index',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],
          [
            'id' =>104,
            'title' => 'Manage Bank',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 103,
            'order' => 1,
            'key'=>'browse_bank',
            'route' => 'bank.index',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],
          [
            'id' =>105,
            'title' => 'Bank Transaction',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 103,
            'order' => 2,
            'key'=>'browse_transaction',
            'route' => 'bank.transactions',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],
          [
            'id' =>106,
            'title' => 'Bank Ledger',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 103,
            'order' => 3,
            'key'=>'browse_ledger',
            'route' => 'bank.ledgers',
            'model_name' => 'Modules\Bank\Entities\Bank'
          ],[
            'id' =>107,
            'title' => 'Company Settings',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => null,
            'order' => 15,
            'key'=>null,
            'route' => 'roles.index',
            'model_name' => 'Modules\Role\Entities\Role'
          ],[
            'id' =>108,
            'title' => 'Roles',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 107,
            'order' => 1,
            'key'=>null,
            'route' => 'roles.index',
            'model_name' => 'Modules\Role\Entities\Role'
          ],[
            'id' =>109,
            'title' => 'Dashboard',
            'menu_id' =>4,
            'icon_class' =>'fa-tachometer-alt',
            'parent_id' => null,
            'order' => 1,
            'key'=>null,
            'route' => 'dashboard',
            'model_name' => null
          ],
        //   [
        //      'id' =>110,
        //      'title' => '',
        //      'menu_id' =>4,
        //      'icon_class' =>'fa-cog',
        //      'parent_id' => null,
        //      'order' => 1,
        //      'key'=>null,
        //      'route' => 'seller.my_sales',
        //      'model_name' => 'Modules\Seller\Entities\Seller'
        //   ],
          [
            'id' =>111,
            'title' => 'New Sales Requisition',
            'menu_id' =>4,
            'icon_class' =>'fa-cog',
            'parent_id' => null,
            'order' => 2,
            'key'=>'add_sells',
            'route' => 'seller.add_sell',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>112,
            'title' => 'Sales Reports',
            'menu_id' =>2,
            'icon_class' =>'fa-chart-line',
            'parent_id' => null,
            'order' => 9,
            'key'=>null,
            'route' => 'reports.index',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>113,
            'title' => 'Sales Person Wise',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 112,
            'order' => 1,
            'key'=>null,
            'route' => 'reports.sales_person',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>114,
            'title' => 'Customer Wise Report',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 112,
            'order' => 2,
            'key'=>null,
            'route' => 'reports.customer_wise',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>115,
            'title' => 'Product Wise Report',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 112,
            'order' => 3,
            'key'=>null,
            'route' => 'reports.product_wise',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>116,
            'title' => 'New Sale Requisition',
            'menu_id' =>3,
            'icon_class' =>'fa-balance-scale',
            'parent_id' => 58,
            'order' => 3,
            'key'=>'add_sells',
            'route' => 'seller.add_sell',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],
          [
            'id' =>117,
            'title' => 'New Sale Requisition',
            'menu_id' =>2,
            'icon_class' =>'fa-balance-scale',
            'parent_id' => 6,
            'order' => 3,
            'key'=>'add_sells',
            'route' => 'seller.add_sell',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],
          [
            'id' =>118,
            'title' => 'Voucher List',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 8,
            'key'=>'browse_voucher',
            'route' => 'accounts.voucher_list',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>119,
            'title' => 'Voucher List',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 8,
            'key'=>'browse_voucher',
            'route' => 'accounts.voucher_list',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>120,
            'title' => 'Collection',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 58,
            'order' => 4,
            'key'=>'add_collection',
            'route' => 'seller.add_collection',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],          [
            'id' =>121,
            'title' => 'Checks In Hand',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 34,
            'order' => 10,
            'key'=>'browse_cih',
            'route' => 'accounts.checks_ih',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>122,
            'title' => 'Company Wise Sales',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 112,
            'order' => 4,
            'key'=>null,
            'route' => 'reports.company_wise',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>123,
            'title' => 'Accounts Reports',
            'menu_id' =>2,
            'icon_class' =>'fa-money-check',
            'parent_id' => null,
            'order' => 11,
            'key'=>null,
            'route' => 'reports.index',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>140,
            'title' => 'Users',
            'menu_id' =>2,
            'icon_class' =>'fas fa-user',
            'parent_id' => null,
            'order' => 14,
            'key'=>null,
            'route' => 'users.index',
            'model_name' => 'App\User'
          ],[
            'id' =>141,
            'title' => 'Manage User',
            'menu_id' =>2,
            'icon_class' =>'fa-users-alt',
            'parent_id' => 140,
            'order' => 2,
            'key'=>null,
            'route' => 'users.user_list',
            'model_name' => 'App\User'
          ]
          ,[
            'id' =>142,
            'title' => 'Users',
            'menu_id' =>3,
            'icon_class' =>'fas fa-user',
            'parent_id' => null,
            'order' => 14,
            'key'=>null,
            'route' => 'users.index',
            'model_name' => 'App\User'
          ],[
            'id' =>143,
            'title' => 'Manage User',
            'menu_id' =>3,
            'icon_class' =>'fa-users-alt',
            'parent_id' => 142,
            'order' => 2,
            'key'=>null,
            'route' => 'users.user_list',
            'model_name' => 'App\User'
          ],[
            'id' =>145,
            'title' => 'Undelivered Sales',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 6,
            'order' => 4,
            'key'=>'browse_sells',
            'route' => 'seller.undelivered_sales',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>146,
            'title' => 'Undelivered Sales',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 58,
            'order' => 4,
            'key'=>'browse_sells',
            'route' => 'seller.undelivered_sales',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>147,
            'title' => 'Undelivered Sales',
            'menu_id' =>4,
            'icon_class' =>'fa-cog',
            'parent_id' => null,
            'order' => 3,
            'key'=>'browse_sells',
            'route' => 'seller.undelivered_sales',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>148,
            'title' => 'Sales Reports',
            'menu_id' =>3,
            'icon_class' =>'fa-chart-line',
            'parent_id' => null,
            'order' => 9,
            'key'=>null,
            'route' => 'reports.index',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>149,
            'title' => 'Sales Person Wise',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 148,
            'order' => 1,
            'key'=>null,
            'route' => 'reports.sales_person',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>150,
            'title' => 'Customer Wise Report',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 148,
            'order' => 2,
            'key'=>null,
            'route' => 'reports.customer_wise',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>151,
            'title' => 'Product Wise Report',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 148,
            'order' => 3,
            'key'=>null,
            'route' => 'reports.product_wise',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>152,
            'title' => 'Company Wise Sales',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 148,
            'order' => 4,
            'key'=>null,
            'route' => 'reports.company_wise',
            'model_name' => 'Modules\Reports\Entities\Report'
          ],[
            'id' =>153,
            'title' => 'Direct Sale',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 6,
            'order' => 5,
            'key'=>'browse_sells',
            'route' => 'seller.direct_sale',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>154,
            'title' => 'Chalan Register',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 112,
            'order' => 6,
            'key'=>'browse_sells',
            'route' => 'seller.manage_chalan',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>155,
            'title' => 'Chalan Register',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 58,
            'order' => 3,
            'key'=>'browse_sells',
            'route' => 'seller.manage_chalan',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],[
            'id' =>156,
            'title' => 'Balance Sheet',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 123,
            'order' => 6,
            'key'=>'browse_balancesheet',
            'route' => 'accounts.balance_sheet',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],[
            'id' =>157,
            'title' => 'Balance Sheet',
            'menu_id' =>3,
            'icon_class' =>'fa-cog',
            'parent_id' => 86,
            'order' => 6,
            'key'=>'browse_balancesheet',
            'route' => 'accounts.balance_sheet',
            'model_name' => 'Modules\Accounts\Entities\Accounts'
          ],
          [
            'id' =>158,
            'title' => 'Fiscal Years',
            'menu_id' =>2,
            'icon_class' =>'fa-cog',
            'parent_id' => 55,
            'order' => 2,
            'key'=>null,
            'route' => 'fiscalyears.index',
            'model_name' => 'Modules\FiscalYears\Entities\FiscalYears'
          ],
          [
            'id' =>163,
            'title' => 'Activity Logs',
            'menu_id' =>2,
            'icon_class' =>'fa-project-diagram',
            'parent_id' => null,
            'order' => 16,
            'key'=>null,
            'route' => 'activityLog.index',
            'model_name' => 'Modules\ActivityLog\Entities\ActivityLog'
          ],
          [
            'id' =>165,
            'title' => 'Rejected Sales',
            'menu_id' =>6,
            'icon_class' =>'fa-cog',
            'parent_id' => 6,
            'order' => 8,
            'key'=>'rejected_sales',
            'route' => 'seller.rejected.sales',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],
          [
            'id' =>166,
            'title' => 'Sales Register',
            'menu_id' =>6,
            'icon_class' =>'fa-cog',
            'parent_id' => 58,
            'order' => 2,
            'key'=>null,
            'route' => 'seller.manage_sales',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],
          [
            'id' =>167,
            'title' => 'Daily Sales Report',
            'menu_id' => 2,
            'icon_class' =>'fa-cog',
            'parent_id' => 112,
            'order' => 2,
            'key'=>null,
            'route' => 'reports.daily_sales_report',
            'model_name' => 'Modules\Seller\Entities\Seller'
          ],
          

          // Next ID Started at 167
                         
      ]);
    }
}
