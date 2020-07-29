<template>
    <div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" class="form-control" placeholder="First Name" v-model="profileDetails.first_name" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" class="form-control" placeholder="Last Name" v-model="profileDetails.last_name" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" class="form-control" placeholder="Age" v-model="profileDetails.age" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <datepicker input-class="form-control date-picker" placeholder="Date of Birth" v-model="profileDetails.birthdate" :minimumView="'day'" :maximumView="'year'"></datepicker>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <select class="custom-select" placeholder="Gender" v-model="profileDetails.gender" required>
                    <option disabled value="null">Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="others">Others</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" class="form-control" placeholder="Address" v-model="profileDetails.address" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-3 mb-3">
                <country-select placeholder="country" class="custom-select" v-model="profileDetails.country" :country="profileDetails.country" topCountry="US" />
            </div>
            <div class="col-md-3 mb-3">
                <region-select class="custom-select" v-model="profileDetails.state" :country="profileDetails.country" :region="profileDetails.state" />
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" placeholder="City" v-model="profileDetails.city" required>
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" placeholder="Zip Code" v-model="profileDetails.zip" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" class="form-control" placeholder="Job Title" v-model="profileDetails.job_title" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <textarea class="form-control" placeholder="Objectives" rows="5" v-model="profileDetails.objectives" required></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-sm btn-secondary" @click="update">Update</button>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {
    props: {
        profile: {
            type: Object,
            default: () => []
        }
    },
    data: function () {
        return {
            profileDetails: this.profile,
        };
    },
    components: { 
        Datepicker
    },
    methods: {
        update: function() {
            let self = this

            self.profileDetails.birthdate = moment(self.profileDetails.birthdate).format('YYYY-MM-DD')

            self.$store.commit('loaded')

            axios.put('/profile/update', self.profileDetails)
            .then(respones => {
                self.$emit('updated')
                self.$store.commit('loaded')
                self.$root.alertSuccess('Profile Saved')
            })
            .catch(error => {
                console.log(error.response)
                self.$store.commit('loaded')
                self.$root.alertFailed('Something went wrong unable to save profile.')
            })
        }
    },
    mounted() {
        let self = this
    }
}
</script>