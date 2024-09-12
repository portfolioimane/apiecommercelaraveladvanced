import { createRouter, createWebHistory } from 'vue-router';
import ProductManager from '../components/ProductManager.vue';
import OrderManager from '../components/OrderManager.vue';
import Dashboard from '../components/Dashboard.vue';
import OrderDetails from '../components/OrderDetails.vue'
const routes = [
   {
    path: '/admin/dashboard',
    name: 'dashboard',
    component: Dashboard,
  },
   {
    path: '/admin/allProducts',
    name: 'product-manager',
    component: ProductManager,
  },
  {
    path: '/admin/allOrders',
    name: 'order-manager',
    component: OrderManager,
  },
{
      path: '/admin/orders/:id',
      name: 'order-details',
      component: OrderDetails,
      props: true,
    },
  
];

const router = createRouter({
  history: createWebHistory(process.env.MIX_APP_URL), // Adjust according to your environment
  routes,
});

export default router;
