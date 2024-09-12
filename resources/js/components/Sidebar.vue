<template>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <router-link class="nav-link" to="/admin/dashboard" active-class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </router-link>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" @click="toggleMenu('productsMenu')">
                    <i class="fas fa-box"></i> Products
                    <i class="fas fa-caret-down ml-auto"></i>
                </a>
                <ul :id="`productsMenu`" class="collapse" :class="{ show: activeMenu === 'productsMenu' }">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin/allProducts">Manage Products</router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Categories</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" @click="toggleMenu('ordersMenu')">
                    <i class="fas fa-shopping-cart"></i> Orders
                    <i class="fas fa-caret-down ml-auto"></i>
                </a>
                <ul :id="`ordersMenu`" class="collapse" :class="{ show: activeMenu === 'ordersMenu' }">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin/allOrders">Manage Orders</router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pending Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Completed Orders</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" @click="toggleMenu('customersMenu')">
                    <i class="fas fa-users"></i> Customers
                    <i class="fas fa-caret-down ml-auto"></i>
                </a>
                <ul :id="`customersMenu`" class="collapse" :class="{ show: activeMenu === 'customersMenu' }">
                    <li class="nav-item">
                        <a class="nav-link" href="#">All Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Customer Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Customer Reviews</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" @click="toggleMenu('reviewsMenu')">
                    <i class="fas fa-star"></i> Reviews
                    <i class="fas fa-caret-down ml-auto"></i>
                </a>
                <ul :id="`reviewsMenu`" class="collapse" :class="{ show: activeMenu === 'reviewsMenu' }">
                    <li class="nav-item">
                        <a class="nav-link" href="#">All Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pending Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Approved Reviews</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" @click="toggleMenu('marketingMenu')">
                    <i class="fas fa-bullhorn"></i> Marketing
                    <i class="fas fa-caret-down ml-auto"></i>
                </a>
                <ul :id="`marketingMenu`" class="collapse" :class="{ show: activeMenu === 'marketingMenu' }">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Promotions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Coupons</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Newsletter</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" @click="toggleMenu('customizeMenu')">
                    <i class="fas fa-cogs"></i> Customize
                    <i class="fas fa-caret-down ml-auto"></i>
                </a>
                <ul :id="`customizeMenu`" class="collapse" :class="{ show: activeMenu === 'customizeMenu' }">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sliders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Testimonials</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" @click="toggleMenu('settingsMenu')">
                    <i class="fas fa-cogs"></i> Settings
                    <i class="fas fa-caret-down ml-auto"></i>
                </a>
                <ul :id="`settingsMenu`" class="collapse" :class="{ show: activeMenu === 'settingsMenu' }">
                    <li class="nav-item">
                        <a class="nav-link" href="#">General Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Payment Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Shipping Settings</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="#">
                    <i class="fas fa-chart-bar"></i> Reports
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
  name: 'Sidebar',
  data() {
    return {
      activeMenu: null
    };
  },
  methods: {
    toggleMenu(menuId) {
      this.activeMenu = this.activeMenu === menuId ? null : menuId;
    }
  }
};
</script>

<style scoped>
    .sidebar {
        background-color: #2d3238;
        width: 250px;
        height: 100vh;
        padding: 1.5rem;
        color: #fff;
        position: fixed;
        overflow-y: auto; /* Makes the sidebar scrollable */
        overflow-x: hidden; /* Hides horizontal overflow */
    }

    .sidebar-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .sidebar-header h2 {
        font-size: 1.5rem;
        color: #f8f9fa;
        font-weight: bold;
    }

    .sidebar ul {
        list-style-type: none;
        padding-left: 0;
        margin: 0;
    }

    .sidebar .nav-link {
        color: #adb5bd;
        padding: 10px 15px;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 1rem;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background-color: #495057;
        color: #fff;
        border-radius: 6px;
    }

    .sidebar .nav-link i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .nav-item {
        margin-bottom: 1rem;
    }

    .collapse {
        display: none;
        padding-left: 1rem;
    }

    .collapse.show {
        display: block;
    }

    .ml-auto {
        margin-left: auto;
    }
</style>
