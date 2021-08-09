<template>
  <div class=container>
        <div class="row justify-content-center">
            <div class=col-md-8>
                <div class=card>
                    <div class=card-header>To-Do App</div>

                    <div class="row">
                        <div class=card-body v-for="toDoItem in toDoItems" :key="toDoItem.id">
                            <div class="card" style="width: 18rem;">
                                <img :src="toDoItem.attachment_url" class="card-img-top" :alt="toDoItem.title">
                                <div class="card-body">
                                    <h5 class="card-title">{{ toDoItem.title }}</h5>
                                    <p class="card-text">{{ toDoItem.body }}</p>

                                    <span v-if="toDoItem.is_complete" class="text-success">Done</span>
                                    <a v-if="! toDoItem.is_complete && showSpinner != toDoItem.id" href="#" class="btn btn-sm btn-primary" @click.prevent="markAsComplete(toDoItem.id, $event)">Done</a>

                                    <div v-show="showSpinner == toDoItem.id" class="spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        showSpinner: null,
        toDoItems: [],
      }
    },

    mounted() {
        axios({url: Laravel.baseUrl + '/to-do', method: 'GET', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
        .then(response => {
            let apiResponse = response.data

            this.toDoItems = apiResponse.data
        })
        .catch(error => {
            if (error.response) {
                let apiData = error.response.data
                this.$toastr.e(apiData.message);
            }
        })
    },

    methods: {
      markAsComplete(itemId) {
        this.showSpinner = itemId

        axios({url: Laravel.baseUrl + `/to-do/${itemId}/mark-as-complete`, method: 'POST', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
        .then(response => {
            let apiResponse = response.data
            this.showSpinner = null

            // this.toDoItems[itemId] = apiResponse.data
            this.toDoItems[itemId]['is_complete'] = true
        })
        .catch(error => {
            if (error.response) {
                let apiData = error.response.data
                this.$toastr.e(apiData.message);
            }
        })

      },

      update() {

      },

      delete() {

      },

      createReminder() {

      },

    }
  }
</script>
