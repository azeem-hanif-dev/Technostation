<template>
  <div>
    <br />
    <h2>{{ translations.add_new }}</h2>

    <div v-if="showLoading" class="loading-overlay">
      <div class="loading-spinner"></div>
    </div>

    <form @submit.prevent="createProject">
      <!-- SECTION 1 -->
      <div class="div-margin">
        <div class="row" style="margin: 5px;">
          <div class="col-md-6 form-group">
            <span>{{ translations.name }}*:</span><br />
            <input
              class="form-control"
              v-model.trim="name"
              type="text"
              :placeholder="translations.name"
              required
            />
            <br />

            <span>{{ translations.customer }}*:</span><br />
            <v-select
              v-model="customer"
              :options="customers"
              label="name"
              :reduce="c => c.id"
              @input="getDepartments"
              :placeholder="translations.customer"
            />
            <br />

            <span>{{ translations.department }}*:</span><br />
            <v-select
              v-model="department"
              :options="departments"
              label="name"
              :reduce="d => d.id"
              @input="getContacts"
              :placeholder="translations.department"
            />
            <br />

            <span>{{ translations.contact }}*:</span><br />
            <v-select
              v-model="performer"
              :options="formattedContacts"
              label="fullName"
              :reduce="c => c.id"
              :placeholder="translations.contact"
            />
          </div>

          <div class="col-md-6 form-group">
            <label>{{ translations.address }}:*</label>
            <input
              type="text"
              v-model.trim="address"
              class="form-control"
              :placeholder="translations.address"
              required
            />
            <br />

            <span>{{ translations.start_date }}:</span>
            <input type="date" v-model="start_date" class="form-control" />
            <br />

            <span>{{ translations.end_date }}:</span>
            <input type="date" v-model="end_date" class="form-control" />
            <br />

            <span>{{ translations.unit }}:</span><br />
            <select class="form-control" v-model="unit">
              <option :value="1">{{ translations.unit }} (1)</option>
              <option :value="2">{{ translations.price }} / Project</option>
            </select>
          </div>
        </div>
      </div>

      <!-- SECTION 2 -->
      <div class="div-margin">
        <div class="row" style="margin: 5px;">
          <div class="col-md-6 form-group">
            <span>{{ translations.post_code }}:</span><br />
            <input
              class="form-control"
              v-model.trim="postcode"
              type="text"
              :placeholder="translations.post_code"
            />
            <br />

            <span>{{ translations.city }}:</span><br />
            <input
              class="form-control"
              v-model.trim="city"
              type="text"
              :placeholder="translations.city"
            />
            <br />

            <span>{{ translations.description }}:</span><br />
            <input
              class="form-control"
              v-model.trim="description"
              type="text"
              :placeholder="translations.description"
            />
            <br />

            <span>{{ translations.fixed_price }}:</span><br />
            <input
              class="form-control"
              v-model.number="fixed_price"
              type="number"
              :placeholder="translations.fixed_price"
            />
            <br />

            <span>{{ translations.ecu_project }}:</span><br />
            <input
              class="form-control"
              v-model.trim="ecu_project_no"
              type="text"
              :placeholder="translations.ecu_project"
            />
            <br />

            <span>{{ translations.notes }}:</span><br />
            <textarea
              class="form-control"
              v-model.trim="notes"
              :placeholder="translations.notes"
            />
            <br />
          </div>

          <div class="col-md-6 form-group">
            <span>{{ translations.client_project_no }}:</span><br />
            <input
              class="form-control"
              v-model.trim="client_project_no"
              type="text"
              :placeholder="translations.client_project_no"
            />
            <br />

            <span>{{ translations.price }}:</span><br />
            <input
              class="form-control"
              v-model.trim="price"
              type="text"
              :placeholder="translations.price"
            />
            <br />

            <span>{{ translations.purchase_price }}:</span><br />
            <input
              class="form-control"
              v-model.trim="purchase_price"
              type="text"
              :placeholder="translations.purchase_price"
            />
            <br />

            <span>{{ translations.approval }}:</span><br />
            <input
              class="form-control"
              v-model.trim="approval"
              type="text"
              :placeholder="translations.approval"
            />
            <br />

            <span>{{ translations.number_of_chain }}:</span><br />
            <input
              class="form-control"
              v-model.trim="number_of_chain"
              type="text"
              :placeholder="translations.number_of_chain"
            />
            <br />

            <span>{{ translations.more_notes }}:</span><br />
            <textarea
              class="form-control"
              v-model.trim="more_notes"
              :placeholder="translations.notes"
            />
          </div>
        </div>
      </div>

      <!-- SECTION 3 -->
      <div class="div-margin">
        <div class="row" style="margin: 5px;">
          <div class="col-md-6 form-group">
            <span>{{ translations.price_agreement }}:</span><br />
            <select class="form-control" v-model="price_agreement">
              <option :value="1">At a time</option>
              <option :value="2">Per hour</option>
            </select>
            <br />

            <span>{{ translations.active }}:</span><br />
            <input
              style="width: 35px;"
              class="form-control"
              type="checkbox"
              v-model="active"
              :true-value="1"
              :false-value="0"
            />
          </div>

          <div class="col-md-6 form-group">
            <span>{{ translations.number_of_times }}:</span><br />
            <input
              class="form-control"
              v-model.trim="number_of_time_per_week"
              type="text"
              :placeholder="translations.number_of_times"
            />
          </div>
        </div>

        <!-- Documents -->
        <div class="row" style="margin: 5px 5px 0 5px;">
          <div class="col-md-12">
            <span>Upload Documents</span>
          </div>
        </div>

        <div
          class="row"
          style="margin: 5px;"
          v-for="(document_log, i) in document_logs"
          :key="i"
        >
          <div class="col-md-3">
            <AutoSuggestInput
              v-model="document_log.doc_type"
              :placeholder="translations.description"
            />
          </div>
          <div class="col-md-2">
            <input
              class="form-control"
              v-model="document_log.expiry"
              type="date"
            />
          </div>
          <div class="col-md-4">
            <input
              class="btn"
              accept=".pdf,.csv,.xls,.xlsx,.docx,application/pdf,text/csv,application/csv,application/excel,application/vnd.msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
              type="file"
              name="document"
              @change="fileUpload($event, i)"
            />
          </div>
          <div class="col-md-3 mt-2">
            <a
              href="#"
              :class="document_logs.length == 1 ? 'disabled' : ''"
              class="text-danger"
              @click.prevent="removeDocument(document_log)"
            >
              <i class="text-danger fa fa-minus-circle"></i>
            </a>
            <a
              v-if="document_logs.length === i + 1"
              href="#"
              class="text-success"
              @click.prevent="addDocument"
            >
              <i class="fa fa-plus-circle bg-plus"></i>
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12 text-right pe-5">
            <a
              :href="base_url + 'staffing_projects'"
              class="mt-2 btn btn-danger btn-xs"
            >
              {{ translations.back }}
            </a>
            <button
              type="submit"
              class="submit btn btn-primary"
              :disabled="showLoading || is_submitting"
            >
              {{ translations.submit }}
            </button>
          </div>
        </div>

      </div>
    </form>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  name: "StaffingProjectCreate",
  props: ["customers", "translations", "common"],
  components: { vSelect },

  data: function () {
    return {
      // docs
      document_log: { doc_type: "", expiry: "", file: null },
      document_logs: [],

      // flags
      is_submitting: false,
      showLoading: false,

      // lists
      departments: [],
      contacts: [],

      // fields
      name: "",
      customer: "",
      department: "",
      performer: null,
      active: 0,
      start_date: "",
      end_date: "",
      project_manager: "2",
      description: "",
      fixed_price: "",
      ecu_project_no: "",
      client_project_no: "",
      address: "",
      postcode: "",
      city: "",
      weekly_statement: "",
      price_agreement: "",
      number_of_time_per_week: "",
      unit: "",
      number_of_chain: "",
      price: "",
      purchase_price: "",
      approval: "",
      notes: "",
      more_notes: "",
      base_url: APP_URL
    };
  },

  computed: {
    formattedContacts: function () {
      var out = [];
      for (var i = 0; i < this.contacts.length; i++) {
        var c = this.contacts[i];
        var full = (c.id || "") + " " + (c.first_name || "") + " " + (c.last_name || "");
        var copy = Object.assign({}, c);
        copy.fullName = full.trim();
        out.push(copy);
      }
      return out;
    }
  },

  beforeMount: function () {
    // start with one empty row
    this.document_logs.push(Object.assign({}, this.document_log));
  },

  methods: {
    addDocument: function () {
      this.document_logs.push(Object.assign({}, this.document_log));
    },
    removeDocument: function (row) {
      if (this.document_logs.length > 1) {
        this.document_logs = this.document_logs.filter(function (d) {
          return d !== row;
        });
      }
    },
    fileUpload: function (event, index) {
      var file = (event && event.target && event.target.files && event.target.files[0]) ? event.target.files[0] : null;
      this.$set(this.document_logs[index], "file", file);
    },

    getDepartments: function () {
      var _this = this;
      axios
        .get(APP_URL + "customers/" + this.customer + "/departments")
        .then(function (res) {
          _this.departments = res.data;
          _this.department = null;
          _this.contacts = [];
          _this.performer = null;
        })
        .catch(function (e) {
          console.log(e);
        });
    },

    getContacts: function () {
      var _this = this;
      axios
        .get(APP_URL + "departments/" + this.department + "/contacts")
        .then(function (res) {
          _this.contacts = res.data;
          _this.performer = null;
        })
        .catch(function (e) {
          console.log(e);
        });
    },

    // --- NEW: per-row document validation ---
    validateDocuments: function () {
      var errors = [];
      for (var i = 0; i < this.document_logs.length; i++) {
        var log = this.document_logs[i] || {};
        var hasAny = (log.doc_type && String(log.doc_type).trim()) || log.expiry || log.file;
        if (hasAny) {
          var missing = [];
          if (!log.doc_type || !String(log.doc_type).trim()) missing.push(this.translations.description || "Type");
          if (!log.expiry) missing.push("Expiry");
          if (!log.file) missing.push("File");
          if (missing.length) {
            errors.push("#" + (i + 1) + ": " + missing.join(", ") + " required");
          }
        }
      }
      if (errors.length) {
        Swal.fire({
          icon: "error",
          title: "Invalid Documents",
          html: errors.join("<br>")
        });
        return false;
      }
      return true;
    },

    validateBeforeSubmit: function () {
      if (!this.name || !this.name.trim()) return this.fail("Please enter " + this.translations.name + ".");
      if (!this.customer) return this.fail("Please select " + this.translations.customer + ".");
      if (!this.department) return this.fail("Please select " + this.translations.department + ".");
      if (!this.performer) return this.fail("Please select " + this.translations.contact + ".");
      if (!this.address || !this.address.trim()) return this.fail("Please enter " + this.translations.address + ".");
      return true;
    },

    fail: function (msg) {
      Swal.fire({ icon: "warning", title: (this.common && this.common.back) ? this.common.back : "Back", text: msg });
      return false;
    },

    createProject: function () {
      if (!this.validateBeforeSubmit()) return;
      if (!this.validateDocuments()) return;

      var _this = this;
      this.is_submitting = true;
      this.showLoading = true;

      var formData = new FormData();
      formData.append("name", this.name);
      formData.append("customer", this.customer);
      formData.append("department", this.department);
      formData.append("performer", this.performer);
      formData.append("active", this.active ? 1 : 0);
      formData.append("start_date", this.start_date || "");
      formData.append("end_date", this.end_date || "");
      formData.append("project_manager", this.project_manager || "");
      formData.append("description", this.description || "");
      formData.append("fixed_price", this.fixed_price || "");
      formData.append("ecu_project_no", this.ecu_project_no || "");
      formData.append("client_project_no", this.client_project_no || "");
      formData.append("address", this.address || "");
      formData.append("postcode", this.postcode || "");
      formData.append("city", this.city || "");
      formData.append("weekly_statement", this.weekly_statement || "");
      formData.append("price_agreement", this.price_agreement || "");
      formData.append("number_of_time_per_week", this.number_of_time_per_week || "");
      formData.append("unit", this.unit || "");
      formData.append("number_of_chain", this.number_of_chain || "");
      formData.append("price", this.price || "");
      formData.append("purchase_price", this.purchase_price || "");
      formData.append("approval", this.approval || "");
      formData.append("notes", this.notes || "");
      formData.append("more_notes", this.more_notes || "");

      // append ONLY complete document rows (avoid undefined index on backend)
      var completeCount = 0;
      for (var i = 0; i < this.document_logs.length; i++) {
        var log = this.document_logs[i] || {};
        if (log.doc_type && String(log.doc_type).trim() && log.expiry && log.file) {
          formData.append("document_logs[" + i + "][doc_type]", log.doc_type);
          formData.append("document_logs[" + i + "][expiry]", log.expiry);
          formData.append("document_logs[" + i + "][file]", log.file);
          completeCount++;
        }
      }

      axios
        .post(APP_URL + "staffing_projects", formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then(function (resp) {
          var okMsg = (resp && resp.data && resp.data.message)
            ? resp.data.message
            : (_this.translations.project_create || "Project created successfully!");
          Swal.fire("Good Job", okMsg, "success");
          setTimeout(function () {
            window.location.href = _this.base_url + "staffing_projects";
          }, 1200);
        })
        .catch(function (error) {
          var msg = "An unexpected error occurred.";
          if (error && error.response && error.response.data) {
            if (error.response.data.metadata && error.response.data.metadata.message) {
              msg = error.response.data.metadata.message;
            } else if (error.response.data.message) {
              msg = error.response.data.message;
            }
          }
          Swal.fire("❌", msg, "error");
          console.log(error && (error.response || error));
        })
        .then(function () {
          _this.showLoading = false;
          _this.is_submitting = false;
        });
    }
  }
};
</script>

<style>
form { padding: 10px; }
input { padding: 4px 8px; width: 500px; }
span { font-size: 18px; margin: 4px; font-weight: 500; }
h2 { text-align: center; }
.div-margin { margin: 10px; }
.submit {
  font-size: 15px; color: #fff; background: #2445ff;
  padding: 6px 12px; border: none; margin-top: 8px;
  cursor: pointer; border-radius: 5px;
}
.loading-overlay {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex; justify-content: center; align-items: center; z-index: 9999;
}
.loading-spinner {
  width: 50px; height: 50px; border: 3px solid #f3f3f3;
  border-top: 3px solid #3498db; border-radius: 50%;
  animation: spin 1s linear infinite;
}
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
