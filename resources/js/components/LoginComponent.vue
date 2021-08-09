<template>
    <div class=container>
        <div class="row justify-content-center">
            <div class=col-md-8>
                <div class=card>
                    <div class=card-header>Login</div>

                    <div class=card-body>
                        <form @submit.prevent="login">
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" v-model="email" class="form-control" placeholder="Enter email">
                                <small v-if="errors.email" class="form-text text-muted text-danger">{{ errors.email }}</small>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" v-model="password" class="form-control" placeholder="Password">
                                <small v-if="errors.email" class="form-text text-muted text-danger">{{ errors.password }}</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    data(){
      return {
        email : "",
        password : "",
        errors: {
            email: null,
            password: null,
        },
      }
    },
    methods: {
      login() {
        let email = this.email
        let password = this.password

        this.errors.email = ""
        this.errors.password = ""

        this.$store.dispatch('login', { email, password })
        .then(() => this.$router.push('/to-do'))
        .catch((error) => {
            if (error.response) {
                let apiData = error.response.data
                this.$toastr.e(apiData.message);

                this.errors.email = apiData.errors.email[0] ?? ""
                this.errors.password = apiData.errors.password[0] ?? ""
            }
        })
      }
    }
  }
</script>
