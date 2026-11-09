<template>
  <header>
    <h1>Inventario Web</h1>
  </header>
  <nav>
    <router-link to="/home">Home</router-link>
  </nav>
  <main>
    <router-view></router-view>
  </main>
</template>

<script>

export default {
  name: 'App',
  components: {
    
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
