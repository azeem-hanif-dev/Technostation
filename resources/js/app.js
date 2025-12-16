/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require("./bootstrap");

import Vue from "vue";
import VueFormulate from "@braid/vue-formulate";
import Dropdown from "vue-simple-search-dropdown";
import AutoSuggestInput from "./components/AutoSuggestInput.vue";
Vue.use(VueFormulate);
Vue.use(Dropdown);

//const Vue = require("vue");

//window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component("AutoSuggestInput", AutoSuggestInput);
Vue.component(
    "quotation-create",
    require("./components/Quotations/Create.vue")
);
Vue.component(
    "quotation-update",
    require("./components/Quotations/Update.vue")
);
Vue.component("quotation-show", require("./components/Quotations/Show.vue"));
Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue")
);
Vue.component("create-customer", require("./components/customer/create.vue"));
Vue.component("update-customer", require("./components/customer/update.vue"));
Vue.component("show-customer", require("./components/customer/show.vue"));

Vue.component(
    "create-employment-agency",
    require("./components/employment_agency/create.vue")
);
Vue.component(
    "update-employment-agency",
    require("./components/employment_agency/update.vue")
);
Vue.component(
    "show-employment-agency",
    require("./components/employment_agency/show.vue")
);

Vue.component(
    "create-department",
    require("./components/department/create.vue")
);
Vue.component(
    "update-department",
    require("./components/department/update.vue")
);
Vue.component("show-department", require("./components/department/show.vue"));

Vue.component("create-personnel", require("./components/personnel/create.vue"));
Vue.component("update-personnel", require("./components/personnel/update.vue"));
Vue.component("show-personnel", require("./components/personnel/show.vue"));

Vue.component(
    "update-profile",
    require("./components/user_profile/update.vue")
);

Vue.component("create-project", require("./components/project/create.vue"));
Vue.component("update-project", require("./components/project/update.vue"));
Vue.component("show-project", require("./components/project/show.vue"));

Vue.component("create-contact", require("./components/contact/create.vue"));
Vue.component("update-contact", require("./components/contact/update.vue"));
Vue.component("show-contact", require("./components/contact/show.vue"));

Vue.component(
    "create-maintenance",
    require("./components/camp_maintenances/create.vue")
);
Vue.component(
    "update-maintenance",
    require("./components/camp_maintenances/update.vue")
);
Vue.component(
    "show-maintenance",
    require("./components/camp_maintenances/show.vue")
);

Vue.component(
    "create-employee_function",
    require("./components/employee_function/create.vue")
);
Vue.component(
    "update-employee_function",
    require("./components/employee_function/update.vue")
);
Vue.component(
    "show-employee_function",
    require("./components/employee_function/show.vue")
);

Vue.component(
    "create-project-planning",
    require("./components/project_planning/create.vue")
);
Vue.component(
    "update-project-planning",
    require("./components/project_planning/update.vue")
);

Vue.component(
    "create-request-personnel",
    require("./components/request_personnel/create.vue")
);
Vue.component(
    "update-request-personnel",
    require("./components/request_personnel/update.vue")
);
Vue.component(
    "show-request-personnel",
    require("./components/request_personnel/show.vue")
);

Vue.component(
    "create-order-waste-container",
    require("./components/order_waste_container/create.vue")
);
Vue.component(
    "update-order-waste-container",
    require("./components/order_waste_container/update.vue")
);
Vue.component(
    "show-order-waste-container",
    require("./components/order_waste_container/show.vue")
);

Vue.component(
    "create-container-supplier",
    require("./components/container_supplier/create.vue")
);
Vue.component(
    "update-container-supplier",
    require("./components/container_supplier/update.vue")
);
Vue.component(
    "show-container-supplier",
    require("./components/container_supplier/show.vue")
);

Vue.component(
    "create-week-state",
    require("./components/week_state/create.vue")
);
Vue.component(
    "update-week-state",
    require("./components/week_state/update.vue")
);
Vue.component(
    "week-weekly-state",
    require("./components/week_state/week-weekly.vue")
);

Vue.component("table1", require("./components/week_state/table.vue"));
Vue.component("show-week-state", require("./components/week_state/show.vue"));

Vue.component("create-comment", require("./components/comments/create.vue"));
Vue.component("update-comment", require("./components/comments/update.vue"));
Vue.component("show-comment", require("./components/comments/show.vue"));

Vue.component(
    "create-user-rights",
    require("./components/user_rights/create.vue")
);
Vue.component(
    "update-user-rights",
    require("./components/user_rights/update.vue")
);

Vue.component(
    "create-employment-agency-overview",
    require("./components/employment_agency_overview/create.vue")
);
Vue.component(
    "email-employment-agency-overview",
    require("./components/employment_agency_overview/email.vue")
);

// Vue.component('create', require('./components/create.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const myapp = new Vue({
    el: "#myapp"
});
