<template>
    <div class="container mt-5">
        <div>
            <b-modal id="add-reminder-modal" title="Reminder me" button-size="sm">
                <template #modal-header>
                    <h5>Reminder me</h5>
                </template>

                <template #default>
                    <b-form v-if="show">
                        <b-form-group id="duration-input-group" label="When:" label-for="duration">
                            <b-form-select
                                v-model="form.when"
                                :options="options"
                                size="sm"
                            ></b-form-select>
                            <small v-for="(error, index) in errors.duration" :key="index" class="form-text text-danger">{{ error }}</small>
                        </b-form-group>
                    </b-form>
                </template>

                <template #modal-footer>
                    <b-button type="reset" class="btn-sm" variant="outline-danger" @click="$bvModal.hide('add-reminder-modal')">Close</b-button>
                    <b-button type="submit" class="btn-sm" variant="primary" @click="addReminder()">Submit</b-button>
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
            required: true
        }
    },
    data() {
        return {
            form: {
                when: { unit: 'day', duration: 1 }
            },
            errors: {
                unit: [],
                duration: [],
            },
            options: [
                { value: null, text: 'Days',  disabled: true },

                { value: { unit: 'day', duration: 1 }, text: 'One day before' },
                { value: { unit: 'day', duration: 2 }, text: 'Two days before' },
                { value: { unit: 'day', duration: 3 }, text: 'Three days before' },
                { value: { unit: 'day', duration: 4 }, text: 'Four days before' },

                { value: null, text: 'Weeks',  disabled: true },

                { value: { unit: 'week', duration: 1 }, text: 'One week before' },
                { value: { unit: 'week', duration: 2 }, text: 'Two weeks before' },
                { value: { unit: 'week', duration: 3 }, text: 'Three weeks before' },
                { value: { unit: 'week', duration: 4 }, text: 'Four weeks before' },

                { value: null, text: 'Months',  disabled: true },

                { value: { unit: 'month', duration: 1 }, text: 'One month before' },
                { value: { unit: 'month', duration: 2 }, text: 'Two months before' },
                { value: { unit: 'month', duration: 3 }, text: 'Three months before' },
                { value: { unit: 'month', duration: 4 }, text: 'Four months before' }
            ],
            showSpinner: false,
            show: true,
        }
    },
    methods: {
        addReminder() {
            let formData = {
                unit: this.form.when.unit,
                duration: this.form.when.duration
            }

            axios({url: Laravel.baseUrl + `/to-do/${this.selectedItem.id}/reminder`, method: 'POST', data: formData, headers: {'Authorization': 'Bearer ' + localStorage.getItem('token') }  })
            .then(response => {
                let apiResponse = response.data

                this.resetForm()
                this.$toastr.s('Reminder added');

                this.$bvModal.hide('add-reminder-modal')
            })
            .catch(error => {
                if (error.response) {
                    let apiData = error.response.data
                    this.$toastr.e(apiData.message);

                    this.errors.unit = apiData.errors.unit ?? []
                    this.errors.duration = apiData.errors.duration ?? []
                } else {
                    console.log(error)
                    this.$toastr.e('An error occured');
                }
            })
        },

        resetForm() {
            // Reset our form values
            this.form.unit = null
            this.form.duration = null
        }
    }
}
</script>

