<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <router-link to=/to-do class=navbar-brand>Navbar</router-link>
        <button class=navbar-toggler type=button data-toggle=collapse data-target=#navbarSupportedContent aria-controls=navbarSupportedContent aria-expanded=false aria-label="Toggle navigation">
            <span class=navbar-toggler-icon></span>
        </button>

        <div class="collapse navbar-collapse" id=navbarSupportedContent>
            <ul class="navbar-nav mr-auto">
                <!-- <li class="nav-item active">
                    <router-link to=/home class=nav-link>Home</router-link>
                </li> -->
                <!-- <li class=nav-item>
                    <router-link to=/login class=nav-link>Login</router-link>
                </li> -->
                <li class=nav-item v-if="isLoggedIn">
                    | <a @click="logout">Logout</a>
                </li>
            </ul>
        </div>
        </nav>
</template>

<script>
  export default {
    computed : {
      isLoggedIn : function(){ return this.$store.getters.isLoggedIn}
    },
    methods: {
      logout: function () {
        this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/login')
        })
      }
    },
  }
</script>
