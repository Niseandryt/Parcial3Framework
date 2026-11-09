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

nav {
  display: flex;
  justify-content: center;
  margin: 24px auto;
}

nav a {
  padding: 10px 22px;
  border-radius: 999px;
  background: #e8f0fe;
  color: #3156a3;
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

nav a:hover,
nav a.router-link-active {
  background: #3156a3;
  color: #fff;
  transform: translateY(-2px);
}

nav a:focus-visible {
  outline: 3px solid #9bb8f5;
  outline-offset: 3px;
}
</style>
