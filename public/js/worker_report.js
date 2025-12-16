new Vue({
    el:'#app',
    data:{
        worker_id:'',
        workers:[],

    },
    mounted(){

    //     this.id = this.$refs.id.value;
    //     axios.get(APP_URL+`getStaffEditDetails/${this.id}`)
    //         .then(response=>{
    //             this.agencies = response.data.agencies;
    //             this.types = response.data.types;
    //             this.user = response.data.user;
    //             this.supervisors = response.data.supervisors;
    //             this.managers = response.data.managers;
    //             this.$swal("Good job!", (this.selectedLanguage == 'en')?"Staff added Successfuly":"Personeel Successfuly toegevoegd", "success");
    //             console.log('user',this.user);
    //         })
    //         .catch(error => {
    //             console.log(error);
    //             this.$swal("Ooops!", (this.selectedLanguage == 'en')?"Add required data":"Vereiste gegevens toevoegen", "error");
    //         });
    },//mounted ends here

    methods:{
        dataTable(){
            $('#table').DataTable({
                processing:true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        },
        getWorkerData(worker){

            this.worker_id=worker.target.value;
            console.log(worker.target.value);
            axios.get(APP_URL+`getWorkerTaskDetails/${this.worker_id}`)
                .then(response=>{
                    this.workers = response.data.worker;
                    //
                    // this.$swal("Good job!", (this.selectedLanguage == 'en')?"Staff added Successfuly":"Personeel Successfuly toegevoegd", "success");
                    // console.log('user',this.user);
                })
                .catch(error => {
                    console.log(error);
                    this.$swal("Ooops!", (this.selectedLanguage == 'en')?"Add required data":"Vereiste gegevens toevoegen", "error");
                });

            // this.worker_id=worker.target.value;
            // console.log(worker.target.value);
            // axios.get(APP_URL+`getWorkerTaskDetails/${this.worker_id}`)
            //     .then(response=>{
            //         this.workers = response.data.worker;
            //
            //         // this.$swal("Good job!", (this.selectedLanguage == 'en')?"Staff added Successfuly":"Personeel Successfuly toegevoegd", "success");
            //         // console.log('user',this.user);
            //     }).then(response=>{
            //         if(response.data.worker.count > 0){
            //
            //         }
            //   this.dataTable();
            // })
            //     .catch(error => {
            //         console.log(error);
            //         this.$swal("Ooops!", (this.selectedLanguage == 'en')?"Add required data":"Vereiste gegevens toevoegen", "error");
            //     });
        }
        // hideShowDiv(){
        //     console.log('hide show role is:', this.staff.role);
        // }, //hideShowDiv ends here

        // editPostcode(){
        //     this.project.city= '';
        //     this.project.address= '';
        //     this.editZipCode = true;
        // },//editPostcode ends here

        // getAddress(){
        //     this.city= '';
        //     this.address= '';
        //     axios.post(APP_URL+`getAddressFromPostCode`,{
        //         'postcode' : this.staff.postcode,
        //         'houseNumber' : this.staff.houseNumber,
        //     }).then(response=>{
        //         console.log('response:',response.data);
        //         this.staff.city = response.data.address.city;
        //         this.staff.address = response.data.address.street;
        //     })
        //         .catch(error=>{
        //             console.log('error:',error);
        //             this.$swal("Error!", (this.selectedLanguage == 'en')?"Invalid zip code or house number" : "Ongeldige postcode of huisnummer", "error");
        //         })
        // },//getAddress ends here
    },

    mounted(){
        this.selectedLanguage = this.$refs.language.value;
    },
})
