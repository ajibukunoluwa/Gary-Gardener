<template>
  <div class=container>
        <div class="row justify-content-center">
            <div class=col-md-8>
                <div class=card>
                    <div class=card-header>To-Do App</div>

                    <div class="row">
                        <div class=card-body v-for="(toDoItem, index) in toDoItems" :key="toDoItem.id">
                            <div class="card" style="width: 18rem;">
                                <img :src="toDoItem.attachment_url" class="card-img-top" :alt="toDoItem.title">
                                <div class="card-body">
                                    <h5 class="card-title">{{ toDoItem.title }}</h5>
                                    <p class="card-text">{{ toDoItem.body }}</p>

                                    <span v-if="toDoItem.is_complete" class="text-success">Done</span>
                                    <a v-else href="#" class="btn btn-sm btn-outline-primary" @click.prevent="markAsComplete(toDoItem.id, index)">
                                        <div v-if="showSpinner == toDoItem.id && action == 'mark-as-done'" class="spinner-border spinner-border-sm" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>

                                        <span v-else>Done</span>
                                    </a>

                                    <!-- Delete button -->
                                    <a href="#" class="btn btn-sm btn-outline-danger" @click.prevent="deleteItem(toDoItem.id, index)">
                                        <div v-if="showSpinner == toDoItem.id && action == 'delete-item'" class="spinner-border spinner-border-sm" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>

                                        <span v-else>Delete</span>
                                    </a>
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
        action: null,
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
      markAsComplete(itemId, index) {
        this.showSpinner = itemId
        this.action = 'mark-as-done'

        axios({url: Laravel.baseUrl + `/to-do/${itemId}/mark-as-complete`, method: 'POST', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
        .then(response => {
            let apiResponse = response.data
            this.showSpinner = null
            this.action = null

            this.toDoItems[index] = apiResponse.data
        })
        .catch(error => {
            if (error.response) {
                let apiData = error.response.data
                this.$toastr.e(apiData.message);
            } else {
                this.$toastr.e('Unknown error occured');
            }
        })

      },

      update() {

      },

      deleteItem(itemId, index) {
        this.showSpinner = itemId
        this.action = 'delete-item'

        let deleteItem = confirm('Are you sure you want to delete this?')

        if (! deleteItem) {
            return;
        }

        axios({url: Laravel.baseUrl + `/to-do/${itemId}`, method: 'DELETE', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
        .then(response => {
            let apiResponse = response.data
            this.showSpinner = null
            this.action = null

            this.toDoItems = this.toDoItems.filter(item => item !== this.toDoItems[index])

        })
        .catch(error => {
            if (error.response) {
                let apiData = error.response.data
                this.$toastr.e(apiData.message);
            } else {
                this.$toastr.e('Unknown error occured');
            }
        })

      },

      createReminder() {

      },

    }
  }
</script>
