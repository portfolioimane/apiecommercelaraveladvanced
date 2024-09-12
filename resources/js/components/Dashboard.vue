<template>
  <div class="container mx-auto p-6">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Admin Dashboard</h1>

    <!-- First Row of Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
      <!-- Card 1 -->
      <div class="card bg-blue-700 text-white">
        <div class="card-content">
          <h5 class="card-title">Total Products</h5>
          <p class="card-value">150</p>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="card bg-green-700 text-white">
        <div class="card-content">
          <h5 class="card-title">Total Sales</h5>
          <p class="card-value">$45,000</p>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="card bg-teal-700 text-white">
        <div class="card-content">
          <h5 class="card-title">Total Orders</h5>
          <p class="card-value">560</p>
        </div>
      </div>
    </div>

    <!-- Second Row of Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Card 4 -->
      <div class="card bg-yellow-500 text-gray-800">
        <div class="card-content">
          <h5 class="card-title">Recent Orders</h5>
          <p class="card-value">10 new orders</p>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="card bg-red-700 text-white">
        <div class="card-content">
          <h5 class="card-title">Customer Feedback</h5>
          <p class="card-value">3 new reviews</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
};
</script>

<style scoped>
/* Container styling */
.container {
  max-width: 1200px;
  padding: 2rem;
}

/* Card styling */
.card {
  border-radius: 0.75rem; /* rounded-lg */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  margin-bottom:5px;
  padding:5px;
}

.card-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.card-title {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.card-value {
  font-size: 2rem;
  font-weight: 700;
}

/* Background colors */
.bg-blue-700 {
  background: #1e3a8a; /* Darker shade of blue */
}

.bg-green-700 {
  background: #2d6a4f; /* Darker shade of green */
}

.bg-teal-700 {
  background: #0f4c4c; /* Darker shade of teal */
}

.bg-yellow-500 {
  background: #fbbf24; /* Standard yellow */
}

.bg-red-700 {
  background: #b91c1c; /* Darker shade of red */
}

/* Card hover effect */
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.card-title{
  color:#fff;
}
.card-value{
  color:#fff;
}
</style>
