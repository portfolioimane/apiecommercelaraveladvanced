<template>
  <div id="app">
    <Sidebar />
    <div class="content-container">
      <router-view />
    </div>
  </div>
</template>

<script>
import Sidebar from './components/Sidebar.vue';

export default {
  name: 'App',
  components: {
    Sidebar
  }
};
</script>

<style>
#app {
  display: flex;
}

.content-container {
  margin-left: 250px; /* Adjust based on the width of your sidebar */
  padding: 1rem;
  width: calc(100% - 250px); /* Adjust based on the width of your sidebar */
  min-height: 100vh;
}

@media (max-width: 768px) {
  .content-container {
    margin-left: 0;
    width: 100%;
  }
}
</style>
