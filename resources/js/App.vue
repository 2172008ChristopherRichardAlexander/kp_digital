<template>
  <div id="app">
    <Sidebar v-if="this.$store.getters.isLoggedIn" />
    <div class="wrapper">
      <div id="page-container">
        <Navbar />
        <div id="content-wrap">
          <main>
            <router-view></router-view>
          </main>
        </div>
        <div id="footer">
          <Footer />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "./components/Navbar";
import Sidebar from "./components/Sidebar";
import Footer from "./components/Footer";
export default {
  name: "app",
  components: {
    Navbar,
    Sidebar,
    Footer
  },
  mounted() {
    this.getListJabatan();
  },
  methods: {
    getListJabatan: function() {
      if (this.$store.getters.isLoggedIn) {
        let id_pengguna = this.$store.getters.pengguna.UserId;
        this.$store
          .dispatch("jabatan", { id_pengguna })
          .then(response => {
            // console.log(response);
          })
          .catch(response => {
            console.log(response);
          });
      }
    }
  }
};
</script>

<style>
* {
  font-family: "Poppins", sans-serif;
  border-radius: 0px;
}
body {
  background-color: rgb(243, 249, 215) !important;
}
.wrapper {
  display: flex;
  align-items: stretch;
  flex-direction: column;
  width: 100vw;
}
#app {
  display: flex;
}
/* Sidebar {
  display: flex;
} */
main {
  flex-grow: 2;
}

#page-container {
  position: relative;
  min-height: 100vh;
}

#content-wrap {
  padding-bottom: 2.5rem; /* Footer height */
}

#footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 2.5rem; /* Footer height */
}

/* // * ############## SCROLL BAR ##############  */
::-webkit-scrollbar {
  width: 5px;
}
/* // ! Warna Background Scroll Bar */
::-webkit-scrollbar-track {
  background: rgb(182, 199, 0);
}
/* // ! Warna Scroll Bar*/
::-webkit-scrollbar-thumb {
  background: rgb(65, 72, 2);
}
</style>