import {createRouter, createWebHistory} from "vue-router";
import AppLayout from '../components/AppLayout.vue';
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import Products from "../views/Products/Products.vue";
import Users from "../views/Users/Users.vue";
import Customers from "../views/Customers/Customers.vue";
import CustomerView from "../views/Customers/CustomerView.vue";
import Orders from "../views/Orders/Orders.vue";
import OrderView from "../views/Orders/OrderView.vue";
import RequestPassword from "../views/RequestPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import store from '../store'
import NotFound from "../views/NotFound.vue";
import Report from "../views/Reports/Report.vue";
import OrdersReport from "../views/Reports/OrdersReport.vue";
import CustomersReport from "../views/Reports/CustomersReport.vue";

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            requiresGuest: true
        }
        
    },
    {
        path: '/request-password',
        name: 'requestPassword',
        component: RequestPassword,
        meta: {
            requiresGuest: true
        }
    },
    {
        path: '/reset-password/:token',
        name: 'resetPassword',
        component: ResetPassword,
        meta: {
            requiresGuest: true
        }
    },
      

    {
        path: '/app',
        name:'app',
        redirect: '/app/dashboard',
        component: AppLayout,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: 'dashboard',
                name: 'app.dashboard',
                component: Dashboard  
            },
            {
                path:'products',
                name: 'app.products',
                component: Products
              },
              {
                path:'users',
                name: 'app.users',
                component: Users
              },
              {
                path:'customers',
                name: 'app.customers',
                component: Customers
              },
              {
                path:'customers/:id',
                name: 'app.customers.view',
                component: CustomerView
              },
              {
                path:'orders',
                name: 'app.orders',
                component: Orders
              },
              {
                path: 'orders/:id',
                name: 'app.orders.view',
                component: OrderView
              },
              {
                path: '/report',
                name: 'reports',
                component: Report,
                meta: {
                  requiresAuth: true
                },
          
                children: [
                  {
                      path: 'orders',
                      name: 'reports.orders',
                      component: OrdersReport
                  },
          
                  {
                      path: 'customers',
                      name: 'reports.customers',
                      component: CustomersReport
                  }
                ]
          
              },
        ]
    },

    {
      path: '/report',
      name: 'report',
      component: Report,
      meta: {
        requiresAuth: true
      },

      children: [
        {
            path: 'orders',
            name: 'report.orders',
            component: OrdersReport
        },

        {
            path: 'customers',
            name: 'report.customers',
            component: CustomersReport
        }
      ]

    },

    {
        path:'/:pathMatch(.*)',
        name:'notfound',
        component:NotFound,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
if(to.meta.requiresAuth && !store.state.user.token) {
    next({name: 'login'})
} else if (to.meta.requiresGuest && store.state.user.token) {

    next({name: 'app.dashboard'})
} else {
    next();
}
}) 



export default router;