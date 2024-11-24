<template>
  <header>
    <!-- <b-navbar class="navbar" toggleable="lg">
    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>-->
    <b-navbar class="navbar">
      <b-collapse id="nav-collapse" is-nav>
        <i @click="toggleNav()" v-if="authUser" class="fas fa-bars toggle-button"></i>
        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <router-link to="/login" class="nav-link navbar-menu-item" v-if="!authUser">Login</router-link>
          <b-nav-item-dropdown right v-else>
            <template v-slot:button-content>
              <em>Hey {{authUser.Name}}</em>
            </template>
            <b-dropdown-item>
              <router-link to="/profile" class="link">Profile</router-link>
            </b-dropdown-item>
            <b-dropdown-item @click="logout()">Log Out</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </header>
</template>
<script>
export default {
  mounted() {},
  computed: {
    authUser() {
      return this.$store.getters.pengguna;
    }
  },
  methods: {
    // logout() {
    //   this.$root.auth = {};
    //   this.$root.auth = null;
    //   localStorage.removeItem("auth");
    // },
    logout: function() {
      //   this.$root.auth = {};
      //   this.$root.auth = null;
      this.$store.dispatch("logout").then(() => this.$router.push("/login"));
    },
    toggleNav() {
      if (this.$store.getters.isLoggedIn) {
        this.$parent.$emit("toggleNav");
      }
    }
  }
};
</script>

<style scoped>
a,a:hover,a:visited,a:active {
  color: black;
  text-decoration: none;
}

.navbar {
  background-color: rgb(182, 199, 0);
  min-height: 56px;
  max-height: 56px;
}
.navbar-menu-item {
  color: rgb(228, 243, 188);
}
.navbar-menu-item:hover {
  color: rgb(255, 255, 255);
}
header {
  width: 100%;
  padding: 0px;
  background-color: rgb(182, 199, 0);
}
.toggle-button {
  padding: 0px 0px 0px 0px;
  font-size: 4vh;
  color: rgb(228, 243, 188);
}
.toggle-button:hover {
  cursor: pointer;
  color: rgb(255, 255, 255);
}
</style>