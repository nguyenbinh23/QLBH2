//Auth
import Home from  './components/Home'
import Login from './components/auth/Login'
import Register from './components/auth/Register'
//

//Customer
import CustomersMain from './components/customers/Main'
import CustomersList from './components/customers/List'
import NewCustomer from './components/customers/New'
import Customer from './components/customers/View'
//

//Category
import CategoriesMain from './components/categories/Main'
import CategoriesList from './components/categories/List'
import CategoriesNew from './components/categories/New'
import Category from './components/categories/View'
//

//Unit
import UnitsMain from './components/units/Main'
import UnitsList from './components/units/List'
import UnitsNew from './components/units/New'
import Unit from './components/units/View'
//

//Product
import ProductsMain from './components/products/Main'
import ProductsList from './components/products/List'
import ProductsNew from './components/products/New'
import Product from './components/products/View'
import ProductImport from './components/products/Import'
//

//Order
import OrdersMain from './components/orders/Main'
import OrdersList from './components/orders/List'
import OrdersNew from './components/orders/New'
import Order from './components/orders/View'
//

//Statistic
import Statistic from './components/statistic/Main'
//

//Admin
import Admin from './components/Admin'
//
//NotAllowed
import NotAllowed from './components/NotAllowed'
//

//Error404
import Error404 from './components/Error404'
//
export const routes = [
    //Homepage
    {
        path: '/',
        component: Home,
        name: 'home-page',
    },
    //Login
    {
        name: 'login',
        path: '/login',
        component: Login,
        props: {
            default: true
        }
    },
    //Register
    {
        path: '/register',
        component: Register,
    },
    //NotAllowed
    {
        path: '/notallowed',
        name: 'not-allowed',
        component: NotAllowed
    },

    //Admin
    {
        path: '/admin',
        component: Admin,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        },
        children: [
            //Customers
            {
                path: 'customers',
                component: CustomersMain,
                children: [
                    {
                        path: '/',
                        component: CustomersList,
                    },
                    {
                        path: 'new',
                        component: NewCustomer
                    },
                    {
                        path: ':id',
                        component: Customer,
                        name: 'customer-edit',
                        props: {

                        }
                    }
                ]
            },
            //

            //Categories
            {
                path: 'categories',
                component: CategoriesMain,
                children: [
                    {
                        path: '/',
                        component: CategoriesList,
                        name: 'categories-list'
                    },
                    {
                        path: 'new',
                        component: CategoriesNew,
                        name: 'category-new'
                    },
                    {
                        path: ':id',
                        component: Category,
                        name: 'category-edit'
                    }
                ]
            },
            //

            //Units
            {
                path: 'units',
                component: UnitsMain,
                children: [
                    {
                        path: '/',
                        component: UnitsList,
                        name: 'units-list'
                    },
                    {
                        path: 'new',
                        component: UnitsNew,
                        name: 'unit-new'
                    },
                    {
                        path: ':id',
                        component: Unit,
                        name: 'unit-edit'
                    }
                ]
            },
            //

            //Products
            {
                path: 'products',
                component: ProductsMain,
                children: [
                    {
                        path: '/',
                        component: ProductsList,
                        name: 'products-list'
                    },
                    {
                        path: 'new',
                        component: ProductsNew,
                        name: 'product-new'
                    },
                    {
                        path: 'import',
                        component: ProductImport,
                        name: 'product-import'
                    },
                    {
                        path: ':id',
                        component: Product,
                        name: 'product-edit'
                    },
                ]
            },
            //

            //Orders
            {
                path: 'orders',
                component: OrdersMain,
                children: [
                    {
                        path: '/',
                        component: OrdersList,
                        name: 'orders-list'
                    },
                    {
                        path: 'new',
                        component: OrdersNew,
                        name: 'order-new'
                    },
                    {
                        path: ':id',
                        component: Order,
                        name: 'order-edit'
                    }
                ]
            },
            //

            //Statistic
            {
                path: 'statistic',
                name: 'statistic',
                component: Statistic,
            },
            //
        ]
    },

    //Error404
    {
        path: '/error404',
        name: 'error404',
        component: Error404
    },
    {
        path: '*',
        redirect: '/error404'
    }
    //
];

