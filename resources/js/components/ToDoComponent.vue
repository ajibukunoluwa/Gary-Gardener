<template>
  <div class=container>
        <div class="row justify-content-center">
            <div class=col-md-8>
                <div class="card-deck">
                    <div class=card>
                        <div class=card-header>
                            To-Do App <b-button variant="primary" class="btn-sm float-right" @click="selectedItem = null" v-b-modal.add-to-do-item-modal>Add</b-button>
                        </div>
                        <div class="row justify-content-center">
                            <b-form-select
                                v-model="selectedFilter"
                                :options="filters"
                                @change="getItems()"
                                size="sm"
                                class="m-md-2 col-md-6"
                            >
                            </b-form-select>

                            <div class="mt-3 text-capitalize text-center col-md-6" v-if="selectedFilter">{{ selectedFilter }} items</div>
                        </div>

                        <div class="row">
                            <template v-if="toDoItems.length">
                                <div v-for="(toDoItem, index) in toDoItems" class=card-body :key="toDoItem.id">
                                    <div class="card" style="width: 18rem;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ toDoItem.title }}</h5>
                                            <p class="card-text text-muted">{{ toDoItem.body }}</p>
                                            <p><a v-if="toDoItem" :href="toDoItem.attachment_url" target="_blank">View file</a></p>

                                            <span v-if="toDoItem.is_complete" class="text-success">Done</span>
                                            <a v-else href="#" class="btn btn-sm btn-outline-success" @click.prevent="markAsComplete(toDoItem.id, index)">
                                                <div v-if="spinItem == toDoItem.id && action == 'mark-as-done'" class="spinner-border spinner-border-sm" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>

                                                <span v-else>Mark Done</span>
                                            </a>
                                            <b-button variant="outline-primary" class="btn-sm" @click="selectedItem = toDoItem" v-b-modal.add-to-do-item-modal>Edit</b-button>

                                            <!-- Delete button -->
                                            <a href="#" class="btn btn-sm btn-outline-danger float-right" @click.prevent="deleteItem(toDoItem.id, index)">
                                                <div v-if="spinItem == toDoItem.id && action == 'delete-item'" class="spinner-border spinner-border-sm" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>

                                                <span v-else>Delete</span>
                                            </a>
                                        </div>
                                        <div class="card-footer">
                                            <small class="text-muted" v-if="toDoItem.due_date_for_humans">{{ toDoItem.due_date_for_humans }}</small>
                                            <b-button variant="light" class="float-right btn-sm" @click="selectedItem = toDoItem" v-b-modal.add-reminder-modal>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                                </svg>
                                            </b-button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div v-if="showSpinner">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                No record available. <b-button variant="primary" class="btn-sm float-right" @click="selectedItem = null" v-b-modal.add-to-do-item-modal>Add</b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <add-to-do-component @addedNewItem="getItems(true)" :selectedItem="selectedItem"></add-to-do-component>
        <add-reminder-component v-if="selectedItem" :selectedItem="selectedItem"></add-reminder-component>
    </div>
</template>


<script>
import AddToDoComponent from './AddToDoComponent.vue'
import AddReminderComponent from './AddReminderComponent.vue'

  export default {
    components: {
        AddToDoComponent,
        AddReminderComponent
    },
    data(){
      return {
        showSpinner: null,
        spinItem: null,
        action: null,
        toDoItems: [],
        selectedItem: null,
        selectedFilter: '',
        filters: [
            { value: null, text: 'All' },
            { value: 'complete', text: 'Complete' },
            { value: 'incomplete', text: 'Incomplete' },
        ]
      }
    },

    mounted() {
        this.getItems()
    },

    methods: {
        getItems(afresh) {
            this.showSpinner = true

            if (afresh) {
                this.selectedFilter = null
            }

            axios({url: Laravel.baseUrl + `/to-do?${this.selectedFilter}`, method: 'GET', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
            .then(response => {
                this.showSpinner = null
                let apiResponse = response.data
                this.toDoItems = apiResponse.data
                this.action = null
            })
            .catch(error => {
                if (error.response) {
                    let apiData = error.response.data
                    this.$toastr.e(apiData.message);
                }
            })
        },

        markAsComplete(itemId, index) {
            this.spinItem = itemId
            this.action = 'mark-as-done'

            axios({url: Laravel.baseUrl + `/to-do/${itemId}/mark-as-complete`, method: 'POST', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
            .then(response => {
                let apiResponse = response.data
                this.spinItem = null
                this.action = null

                this.toDoItems[index] = apiResponse.data
            })
            .catch(error => {
                if (error.response) {
                    let apiData = error.response.data
                    this.$toastr.e(apiData.message);
                } else {
                    this.$toastr.e('An error occured');
                }
            })

        },

        deleteItem(itemId, index) {
            let deleteItem = confirm('Are you sure you want to delete this?')
            if (! deleteItem) { return; }

            this.spinItem = itemId
            this.action = 'delete-item'

            axios({url: Laravel.baseUrl + `/to-do/${itemId}`, method: 'DELETE', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
            .then(response => {
                let apiResponse = response.data
                this.spinItem = null
                this.action = null

                this.toDoItems = this.toDoItems.filter(item => item !== this.toDoItems[index])

            })
            .catch(error => {
                if (error.response) {
                    let apiData = error.response.data
                    this.$toastr.e(apiData.message);
                } else {
                    this.$toastr.e('An error occured');
                }
            })
        },
    }
  }
</script>
