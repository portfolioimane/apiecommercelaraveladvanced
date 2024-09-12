import { createApp } from 'vue';
import App from './App.vue'; // Main App component that includes router-view
import router from './router/index.js'; // Import router configuration
import './bootstrap.js'; // Import bootstrap

// Create Vue app instance
const app = createApp(App);

// Use Vue Router
app.use(router);

// Mount Vue app to the main element
app.mount('#appadmin');


