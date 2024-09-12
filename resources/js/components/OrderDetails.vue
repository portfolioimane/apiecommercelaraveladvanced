<template>
  <div class="order-details-container">
    <h1 class="page-title">Order Details</h1>

    <div v-if="loading" class="loading">
      Loading order details...
    </div>

    <div v-if="!loading && selectedOrder" class="order-details">
      <h6><strong>User:</strong> {{ selectedOrder.user.name }}</h6>
      <h6><strong>Total Amount:</strong> {{ selectedOrder.total_amount }} MAD</h6>
      <h6><strong>Payment Method:</strong> {{ selectedOrder.payment_method }}</h6>
      <h6><strong>Status:</strong> {{ selectedOrder.status }}</h6>

      <!-- Display order items -->
      <table class="table table-striped mt-3">
        <thead class="bg-light">
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in selectedOrder.order_items" :key="item.id">
            <td>{{ item.product.name }}</td>
            <td>{{ item.quantity }}</td>
            <td>{{ item.price }} MAD</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="!loading && !selectedOrder" class="alert alert-warning text-center">
      No order details found.
    </div>

    <router-link to="/admin/allOrders" class="btn btn-secondary">Back to Orders</router-link>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      selectedOrder: null,
      loading: false,
    };
  },
  methods: {
    fetchOrderDetails() {
      const orderId = this.$route.params.id;
      this.loading = true;
      axios
        .get(`/admin/orders/${orderId}`)
        .then((response) => {
          this.selectedOrder = response.data;
          this.loading = false;
        })
        .catch((error) => {
          console.error('Error fetching order details:', error);
          this.loading = false;
        });
    },
  },

  // Fetch order details when the component is mounted
  mounted() {
    this.fetchOrderDetails();
  },
};
</script>



<style scoped>
.order-details-page {
  padding: 20px;
}

.page-title {
  font-size: 24px;
  color: #007bff;
  margin-bottom: 20px;
  text-align: center;
  text-transform: uppercase;
}

.loading {
  font-size: 18px;
  font-weight: bold;
  color: #333;
  text-align: center;
}

.order-details h6 {
  margin-bottom: 10px;
  color: #333;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}
</style>
