<template>
    <div class="container mt-5">
        <div>
            <b-modal id="add-to-do-item-modal" title="Add to list" button-size="sm">
                <template #modal-header>
                    <h5 v-if="isUpdateRequest()">Update Item</h5>
                    <h5 v-else>Add to list</h5>
                </template>

                <template #default>
                    <b-form v-if="show">
                        <b-form-group id="title-input-group" label="Title:" label-for="title">
                            <b-form-input
                            id="title"
                            v-model="form.title"
                            type="text"
                            placeholder="Enter title"
                            required
                            ></b-form-input>
                            <small v-for="(error, index) in errors.title" :key="index" class="form-text text-danger">{{ error }}</small>
                        </b-form-group>

                        <b-form-group id="body-input-group" label="Body:" label-for="body-input">
                            <b-form-textarea
                                id="body-input"
                                v-model="form.body"
                                placeholder="Enter a short description"
                                rows="3"
                                max-rows="6"
                                required
                            ></b-form-textarea>
                            <small v-for="(error, index) in errors.body" :key="index" class="form-text text-danger">{{ error }}</small>
                        </b-form-group>

                        <b-form-group id="attachment-input-group" label="Media:" label-for="attachment-input">
                            <b-form-file
                                id="attachment-input"
                                placeholder="Choose a file or drop it here..."
                                size="sm"
                                v-model="form.attachment"
                                required
                            ></b-form-file>
                            <small v-for="(error, index) in errors.attachment" :key="index" class="form-text text-danger">{{ error }}</small>
                            <a v-if="selectedItem" :href="selectedItem.attachment_url" target="_blank">View existing file</a>
                        </b-form-group>

                        <b-form-group id="due-date-input-group" label="Due date:" label-for="due-date-input">
                            <b-form-datepicker
                                id="due-date-input"
                                v-model="form.due_date"
                                class="mb-2"
                            ></b-form-datepicker>
                            <small v-for="(error, index) in errors.due_date" :key="index" class="form-text text-danger">{{ error }}</small>
                        </b-form-group>

                    </b-form>
                </template>

                <template #modal-footer>
                    <b-button type="submit" class="btn-sm" variant="primary" @click="submitForm()">Submit</b-button>
                    <b-button type="reset" class="btn-sm" variant="danger" @click="resetForm()">Reset</b-button>
                </template>

            </b-modal>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        selectedItem: {
            type: Object,
            required: false
        }
    },
    data() {
        return {
            form: {
                title: this.selectedItem?.title ?? '',
                // title: this.selectedItem,
                body: this.selectedItem?.body ?? '',
                due_date: '',
                attachment: null
            },
            errors: {
                title: [],
                body: [],
                due_date: [],
                attachment: []
            },
            show: true
        }
    },
    methods: {
        submitForm(event) {
            let formData = new FormData();
            formData.append('title', this.form.title);
            formData.append('body', this.form.body);
            formData.append('due_date', this.form.due_date);
            formData.append('attachment', this.form.attachment);

            axios({url: this.url(), method: 'POST', data: formData, headers: { 'Content-Type': 'multipart/form-data', 'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
            .then(response => {
                let apiResponse = response.data

                this.resetForm()
                this.$toastr.s('Item added');

                this.$emit('addedNewItem', apiResponse.data)
                this.$bvModal.hide('add-to-do-item-modal')
            })
            .catch(error => {
                if (error.response) {
                    let apiData = error.response.data
                    this.$toastr.e(apiData.message);

                    this.errors.title = apiData.errors.title ?? []
                    this.errors.body = apiData.errors.body ?? []
                    this.errors.due_date = apiData.errors.due_date ?? []
                    this.errors.attachment = apiData.errors.attachment ?? []
                } else {
                    console.log(error)
                    this.$toastr.e('An error occured');
                }
            })
        },
        url() {
            if (this.isUpdateRequest()) {
                return Laravel.baseUrl + `/to-do/${selectedItem.id}`
            }

            return Laravel.baseUrl + `/to-do`
        },
        isUpdateRequest() {
            return this.selectedItem && typeof this.selectedItem === 'object'
        },
        onReset(event) {
            event.preventDefault()
            this.resetForm()
        },
        resetForm() {
            // Reset our form values
            this.form.title = ''
            this.form.body = ''
            this.form.due_date = ''
            this.form.attachment = null
            // Trick to reset/clear native browser form validation state
            this.show = false
            this.$nextTick(() => {
                this.show = true
            })
        }
    }
}
</script>

