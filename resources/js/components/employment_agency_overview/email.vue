<template>

</template>

<script>
import Swal from "sweetalert2";
import axios from "axios";

export default {
    props: ['personnels','wages','week_state_week_cards','agency_id','week_state_ids','project_id','translations','projects','current_week_no','week_state_over_view'],
    beforeMount() {
        this.personnel_logs.push(Object.assign({}, this.personnel_log));
        this.document_logs.push(Object.assign({}, this.document_log));
    },
    data() {

        return {
            message: "",
            dispatch_date: '',
            receive_date: '',
            invoice_date: '',
            invoice_number: '',
            status: '',
            completed: 0,
            notes: '',
            base_url: APP_URL,
        };
    },
    mounted() {
        console.log("Mounted");

        this.setWeekCardsData();

        for (let i = 0; i < this.week_state_over_view.documents.length; i++) {
            this.document_update_logs  .push(Object.assign({}, this.document_update_log));
            const document_update_log = this.document_update_logs[i];
            document_update_log.doc_type = this.week_state_over_view.documents[i].type
            document_update_log.expiry = this.week_state_over_view.documents[i].expiry_date
            document_update_log.file = this.week_state_over_view.documents[i].file
            document_update_log.id = this.week_state_over_view.documents[i].id
        }

        this.setWeekOverViewData();

        console.log('UPDATE_',this.personnel_update_logs)
    },
    methods: {
        setWeekCardsData(){
            for (let i = 0; i < this.week_state_week_cards.length; i++)
            {
                this.personnel_update_logs  .push(Object.assign({}, this.personnel_update_log));
                const personnel_update_log = this.personnel_update_logs[i];
                personnel_update_log.personnel = this.week_state_week_cards[i].personnel_id
                personnel_update_log.directing = this.week_state_week_cards[i].directing
                personnel_update_log.hours_1 = this.week_state_week_cards[i].hours_1
                personnel_update_log.hours_2 = this.week_state_week_cards[i].hours_2
                personnel_update_log.hours_3 = this.week_state_week_cards[i].hours_3
                personnel_update_log.hours_4 = this.week_state_week_cards[i].hours_4
                personnel_update_log.hours_5 = this.week_state_week_cards[i].hours_5
                personnel_update_log.hours_6 = this.week_state_week_cards[i].hours_6
                personnel_update_log.hours_7 = this.week_state_week_cards[i].hours_7
                personnel_update_log.total_hours = this.week_state_week_cards[i].total_hours
                personnel_update_log.rate = this.week_state_week_cards[i].customer
                personnel_update_log.cost = this.week_state_week_cards[i].cost
                personnel_update_log.directing = this.week_state_week_cards[i].directing
                personnel_update_log.wage = this.week_state_week_cards[i].comments
                personnel_update_log.week_card_id = this.week_state_week_cards[i].id
            }
        },
        setWeekOverViewData(){
            if (this.week_state_over_view !== null) {
                this.dispatch_date = this.week_state_over_view.dispatch_date || '';
                this.receive_date = this.week_state_over_view.receive_date || '';
                this.invoice_date = this.week_state_over_view.invoice_date || '';
                this.invoice_number = this.week_state_over_view.invoice_number || '';
                this.status = this.week_state_over_view.status || '';
                this.completed = this.week_state_over_view.completed ? 1 : 0;
                this.notes = this.week_state_over_view.notes || '';
                this.comments = this.week_state_over_view.comments || '';
            }
        },
        downloadPdf()
        {
            axios.get(`${APP_URL}week-state-pdf`, {
                params: {
                    week_state_ids: JSON.stringify(this.week_state_ids),
                    week_no: this.week_no
                },
                responseType: 'blob'
            }).then(response => {
                const blob = new Blob([response.data], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);

                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'employment_agency.pdf');

                document.body.appendChild(link);
                link.click();

                link.remove();
                window.URL.revokeObjectURL(url);
            });
        },
        removeUpdateDoc(document_update_log) {
            if (this.document_update_logs.length >= 1) {
                // Extract doc_type before removing
                const removedDocId = document_update_log.id;

                // Filter out the document_update_log from the array
                this.document_update_logs = this.document_update_logs.filter(
                    (log) => log !== document_update_log
                );

                // Store the removed doc_type in the array
                this.removedDocumentIds.push(removedDocId);
            }
        },

        deletePersonnel(personnel_update_log) {
            if (this.personnel_update_logs.length >= 1) {

                const removedPersonnelId = personnel_update_log.week_card_id;

                this.personnel_update_logs = this.personnel_update_logs.filter(
                    (log) => log !== personnel_update_log
                );

                this.removedPersonnelIds.push(removedPersonnelId);
            }
        },

        addDocument() {
            this.document_logs.push(Object.assign({}, this.document_log));
        },
        removeDocument(row) {
            if (this.document_logs.length > 1) {
                this.document_logs = this.document_logs.filter(
                    (document_log) => document_log !== row
                );
            }
        },
        fileUpload(event){
            this.document_logs.push(Object.assign({}, this.document_log));

            const document_log = this.document_logs[this.key_value];
            document_log.file = event.target.files[0];

            console.log("FILEEEEEEEEEEEEE"+event.target)

            this.key_value = this.key_value+1;
        },//fileUpload
        getProjectData() {
            axios.get(`${APP_URL}/staffing_projects/${this.project}`)
                .then(response => {
                    const project = response.data
                    this.department = project.department.name
                    this.customer = project.customer.name
                    this.performer = project.performer
                    this.dates = project.dates
                    this.approval = project.approval
                });
        },

        addPersonnel() {
            this.personnel_logs.push(Object.assign({}, this.personnel_log));
        },
        removePersonnel(row) {
            if (this.personnel_logs.length > 1) {
                this.personnel_logs = this.personnel_logs.filter(
                    (personnel_log) => personnel_log !== row
                );
            }
        },

        totalHours()
        {
            for (let i = 0; i < this.personnel_logs.length; i++) {
                const personnel_log = this.personnel_logs[i];

                personnel_log.total_hours = Number(personnel_log.hours_1)+
                    Number(personnel_log.hours_2)+
                    Number(personnel_log.hours_3)+
                    Number(personnel_log.hours_4)+
                    Number(personnel_log.hours_5)+
                    Number(personnel_log.hours_6)+
                    Number(personnel_log.hours_7)
            }
        },
        updateTotalHours()
        {
            for (let i = 0; i < this.personnel_update_logs.length; i++) {
                const personnel_update_log = this.personnel_update_logs[i];

                personnel_update_log.total_hours = Number(personnel_update_log.hours_1)+
                    Number(personnel_update_log.hours_2)+
                    Number(personnel_update_log.hours_3)+
                    Number(personnel_update_log.hours_4)+
                    Number(personnel_update_log.hours_5)+
                    Number(personnel_update_log.hours_6)+
                    Number(personnel_update_log.hours_7)
            }
        },
        createWeekStateOverView() {
            console.log(this.request);

            let formData = new FormData();
            formData.append('week_no', this.week_no);
            formData.append('dispatch_date', this.dispatch_date);
            formData.append('receive_date', this.receive_date);
            formData.append('invoice_date', this.invoice_date);
            formData.append('invoice_number', this.invoice_number);
            formData.append('status', this.status);
            formData.append('completed', this.completed ? 1 : 0);
            formData.append('comments', this.comments);
            formData.append('notes', this.notes);
            formData.append('employ_agency_id', this.employ_agency_id);

            this.removedDocumentIds.forEach(id => {
                formData.append('removedDocumentIds[]', id);
            });
            this.week_state_ids.forEach(id => {
                formData.append('week_state_ids[]', id);
            });
            this.removedPersonnelIds.forEach(id => {
                formData.append('removedPersonnelIds[]', id);
            });
            this.document_logs.forEach((log, index) => {
                formData.append(`document_logs[${index}][doc_type]`, log.doc_type);
                formData.append(`document_logs[${index}][expiry]`, log.expiry);
                formData.append(`document_logs[${index}][file]`, log.file);
            });

            this.personnel_logs.forEach((log, index) => {
                formData.append(`personnel_logs[${index}][week_no]`, log.week_no);
                formData.append(`personnel_logs[${index}][personnel]`, log.personnel);
                formData.append(`personnel_logs[${index}][hours_1]`, log.hours_1);
                formData.append(`personnel_logs[${index}][hours_2]`, log.hours_2);
                formData.append(`personnel_logs[${index}][hours_3]`, log.hours_3);
                formData.append(`personnel_logs[${index}][hours_4]`, log.hours_4);
                formData.append(`personnel_logs[${index}][hours_5]`, log.hours_5);
                formData.append(`personnel_logs[${index}][hours_6]`, log.hours_6);
                formData.append(`personnel_logs[${index}][hours_7]`, log.hours_7);
                formData.append(`personnel_logs[${index}][total_hours]`, log.total_hours);
                formData.append(`personnel_logs[${index}][rate]`, log.rate);
                formData.append(`personnel_logs[${index}][cost]`, log.cost);
                formData.append(`personnel_logs[${index}][directing]`, log.directing ? 1 : 0);
                formData.append(`personnel_logs[${index}][wage]`, log.wage);
            });

            this.personnel_update_logs.forEach((log, index) => {
                formData.append(`personnel_update_logs[${index}][week_card_id]`, log.week_card_id);
                formData.append(`personnel_update_logs[${index}][personnel]`, log.personnel);
                formData.append(`personnel_update_logs[${index}][hours_1]`, log.hours_1);
                formData.append(`personnel_update_logs[${index}][hours_2]`, log.hours_2);
                formData.append(`personnel_update_logs[${index}][hours_3]`, log.hours_3);
                formData.append(`personnel_update_logs[${index}][hours_4]`, log.hours_4);
                formData.append(`personnel_update_logs[${index}][hours_5]`, log.hours_5);
                formData.append(`personnel_update_logs[${index}][hours_6]`, log.hours_6);
                formData.append(`personnel_update_logs[${index}][hours_7]`, log.hours_7);
                formData.append(`personnel_update_logs[${index}][total_hours]`, log.total_hours);
                formData.append(`personnel_update_logs[${index}][rate]`, log.rate);
                formData.append(`personnel_update_logs[${index}][cost]`, log.cost);
                formData.append(`personnel_update_logs[${index}][directing]`, log.directing ? 1 : 0);
                formData.append(`personnel_update_logs[${index}][wage]`, log.wage);
            });

            axios.post(APP_URL + 'employment-agency-overview', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {

                Swal.fire(
                    'Good job!',
                    'Employment Agency Overview Successfully!',
                    'success'
                )
            });
        }
    },
};
</script>

<style>
form {
    padding: 10px;
}

span {
    font-size: 18px;
    margin: 4px;
    font-weight: 500;
}

h4 {
    margin: 15px;
}

</style>
