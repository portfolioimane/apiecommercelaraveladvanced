<template>
  <div class="order-management">
    <h1 class="page-title">Order Management</h1>

    <!-- Loading Spinner -->
    <div v-if="loading" class="loading">
      Loading orders...
    </div>

    <!-- Orders Table -->
    <table v-if="!loading && orders.length" class="table table-hover">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Total Amount</th>
          <th>Payment Method</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(order, index) in orders" :key="order.id">
          <td>{{ index + 1 }}</td>
          <td>{{ order.user.name }}</td>
          <td>{{ order.total_amount }} MAD</td>
          <td>{{ order.payment_method }}</td>
          <td>
            <select v-model="order.status" @change="updateStatus(order)" class="form-select">
              <option value="pending">Pending</option>
              <option value="processing">Processing</option>
              <option value="completed">Completed</option>
              <option value="canceled">Canceled</option>
            </select>
          </td>
          <td>
            <!-- Navigate to OrderDetails component -->
            <router-link :to="{ name: 'order-details', params: { id: order.id } }" class="btn btn-primary btn-sm">
              View Details
            </router-link>
            <button @click="deleteOrder(order.id)" class="btn btn-danger btn-sm">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!loading && !orders.length" class="alert alert-warning text-center">
      No orders found.
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      orders: [],
      loading: false,
    };
  },
  methods: {
    // Fetch all orders
    fetchOrders() {
      this.loading = true;
      axios
        .get('/admin/orders')
        .then((response) => {
          this.orders = response.data;
          this.loading = false;
        })
        .catch((error) => {
          console.error('Error fetching orders:', error);
          this.loading = false;
        });
    },

    // Update order status
    updateStatus(order) {
      axios
        .put(`/admin/orders/${order.id}`, { status: order.status })
        .then(() => {
          alert('Order status updated successfully.');
        })
        .catch((error) => {
          console.error('Error updating order status:', error);
        });
    },

    // Delete order
    deleteOrder(orderId) {
      if (confirm('Are you sure you want to delete this order?')) {
        axios
          .delete(`/admin/orders/${orderId}`)
          .then(() => {
            this.orders = this.orders.filter((order) => order.id !== orderId);
            alert('Order deleted successfully.');
          })
          .catch((error) => {
            console.error('Error deleting order:', error);
          });
      }
    },
  },

  // Fetch orders when the component is mounted
  mounted() {
    this.fetchOrders();
  },
};
</script>


<style scoped>
.order-management {
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

.table {
  width: 100%;
  margin-top: 20px;
}

.table th, .table td {
  text-align: center;
  vertical-align: middle;
}

.btn {
  margin-right: 10px;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  border-radius: 4px;
}

.btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
  border-radius: 4px;
}

.modal {
  display: block;

}

.modal-dialog {
  max-width: 800px;
}

.modal-content {
  background-color: #fff;
  border-radius: 8px;
}

.modal-header {
  background-color: #007bff;
  color: #fff;
  border-bottom: none;
}

.modal-header .btn-close {
  background: #fff;
}

.modal-body {
  padding: 20px;
}

.modal-footer {
  border-top: none;
  padding: 10px;
  text-align: right;
}

.modal-title {
  font-size: 20px;
}

.order-details h6 {
  margin-bottom: 10px;
  color: #333;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th, .table-bordered td {
  border: 1px solid #dee2e6;
}
</style>
