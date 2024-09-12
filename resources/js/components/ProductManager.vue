<template>
  <div class="product-manager">
    <h1 class="title">Product Management</h1>
    <div class="actions">
      <button class="btn btn-primary" @click="showCreateProduct">Add New Product</button>
    </div>
    <table class="product-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Category</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.name }}</td>
          <td>{{ product.description }}</td>
          <td>{{ product.price | currency }}</td>
          <td>{{ product.stock }}</td>
          <td>{{ product.category.name }}</td>
          <td>
            <img :src="product.image ? '/storage/images/' + product.image : '/path/to/default/image.png'" alt="Product Image" class="product-image"/>
          </td>
          <td>
            <button class="btn btn-edit" @click="editProduct(product.id)">Edit</button>
            <button class="btn btn-delete" @click="deleteProduct(product.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    
    <!-- Create/Edit Product Modal -->
    <div v-if="showCreateModal || showEditModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h2>{{ isEdit ? 'Edit Product' : 'Create Product' }}</h2>
        <form @submit.prevent="submitForm" class="modal-form" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">Name</label>
            <input id="name" v-model="form.name" placeholder="Name" required>
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" v-model="form.description" placeholder="Description" required></textarea>
          </div>
          <div class="form-group">
            <label for="price">Price</label>
            <input id="price" v-model="form.price" type="number" step="0.01" placeholder="Price" required>
          </div>
          <div class="form-group">
            <label for="stock">Stock</label>
            <input id="stock" v-model.number="form.stock" type="number" placeholder="Stock" required>
          </div>
          <div class="form-group">
            <label for="image">Image</label>
            <input id="image" type="file" @change="handleFileUpload">
            <div v-if="imagePreview">
              <img :src="imagePreview" alt="Image Preview" class="product-image-preview"/>
            </div>
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" v-model="form.category_id" required>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div class="modal-buttons">
            <button type="submit" class="btn btn-primary">{{ isEdit ? 'Update' : 'Create' }}</button>
            <button type="button" class="btn btn-cancel" @click="closeModal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      products: [],
      categories: [],
      form: {
        id: null,
        name: '',
        description: '',
        price: '',
        stock: '',
        image: '',
        category_id: null,
        imageFile: null
      },
      showCreateModal: false,
      showEditModal: false,
      isEdit: false,
      imagePreview: null
    };
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await axios.get('/admin/products');
        console.log('Fetched products:', response.data);
        this.products = response.data;
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    },
    async fetchCategories() {
      try {
        const response = await axios.get('/admin/categories');
        console.log('Fetched categories:', response.data);
        this.categories = response.data;
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    showCreateProduct() {
      this.form = {
        id: null,
        name: '',
        description: '',
        price: '',
        stock: '',
        image: '',
        category_id: null,
        imageFile: null
      };
      this.imagePreview = null; // Reset preview
      this.showCreateModal = true;
      this.isEdit = false;
    },
    async submitForm() {
      let formData = new FormData();
      formData.append('name', this.form.name);
      formData.append('description', this.form.description);
      formData.append('price', this.form.price);
      formData.append('stock', this.form.stock);
      if (this.form.imageFile) {
        formData.append('image', this.form.imageFile);
      }
      formData.append('category_id', this.form.category_id);

      console.log('Form Data:', this.form); // Log the form data
      console.log('Form Data Object:', formData); // Log the FormData object

      try {
        if (this.isEdit) {
         formData.append("_method", "put");
          console.log('Sending PUT request to:', `/admin/products/${this.form.id}`);
          const response = await axios.post(`/admin/products/${this.form.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
          console.log('PUT response:', response);
        } else {
          console.log('Sending POST request to:', '/admin/products');
          const response = await axios.post('/admin/products', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
          console.log('POST response:', response);
        }
        this.fetchProducts();
        this.closeModal();
      } catch (error) {
        console.error('Error during submitForm:', error);
        if (error.response) {
          console.log('Error Response Data:', error.response.data);
          console.log('Error Response Status:', error.response.status);
          console.log('Error Response Headers:', error.response.headers);
        } else if (error.request) {
          console.log('Error Request:', error.request);
        } else {
          console.log('Error Message:', error.message);
        }
      }
    },
    async editProduct(id) {
      try {
        const response = await axios.get(`/admin/products/${id}`);
        console.log('Product data for edit:', response.data);
        this.form = response.data;
        this.imagePreview = this.form.image ? '/storage/images/' + this.form.image : null;
        this.showCreateModal = true;
        this.isEdit = true;
      } catch (error) {
        console.error('Error fetching product for edit:', error);
      }
    },
    async deleteProduct(id) {
      if (confirm('Are you sure you want to delete this product?')) {
        try {
          await axios.delete(`/admin/products/${id}`);
          this.fetchProducts();
        } catch (error) {
          console.error('Error deleting product:', error);
        }
      }
    },
    closeModal() {
      this.showCreateModal = false;
      this.showEditModal = false;
    },
    handleFileUpload(event) {
      this.form.imageFile = event.target.files[0];
      if (this.form.imageFile) {
        console.log('Selected file:', this.form.imageFile);
        this.imagePreview = URL.createObjectURL(this.form.imageFile);
      } else {
        console.log('No file selected');
        this.imagePreview = null;
      }
    }
  },
  created() {
    this.fetchProducts();
    this.fetchCategories();
  }
};
</script>

<style scoped>
/* General Styles */
.product-manager {
  font-family: Arial, sans-serif;
  padding: 20px;
  background-color: #f9f9f9;
}

.title {
  color: #333;
  margin-bottom: 20px;
  font-size: 2em;
}

.actions {
  margin-bottom: 20px;
}

.btn {
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.btn-primary {
  background-color: #007bff;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-edit {
  background-color: #ffc107;
}

.btn-edit:hover {
  background-color: #e0a800;
}

.btn-delete {
  background-color: #dc3545;
}

.btn-delete:hover {
  background-color: #c82333;
}

.btn-cancel {
  background-color: #6c757d; /* Light grey color */
}

.btn-cancel:hover {
  background-color: #5a6268; /* Darker grey color */
}

/* Table Styles */
.product-table {
  width: 100%;
  border-collapse: collapse;
}

.product-table th, .product-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.product-table th {
  background-color: #f4f4f4;
}

/* Modal Styles */
/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 500px;
  max-height: 80vh; /* Limit the maximum height */
  overflow-y: auto; /* Enable vertical scrolling if content exceeds max height */
}

.modal-form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input, .form-group textarea, .form-group select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.modal-buttons {
  display: flex;
  justify-content: space-between;
}

.product-image {
  width: 100px;
  height: auto;
}

.product-image-preview {
  width: 150px;
  height: auto;
}

</style>