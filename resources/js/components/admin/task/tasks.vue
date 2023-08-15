<template>
  <div>
    <h2 class="mb-4 primary--text headline" v-if="!loading">Tasks</h2>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="tasks"
        class="elevation-1"
        v-if="!loading"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="500px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                  :small="$vuetify.breakpoint.mdAndUp"
                  :x-small="$vuetify.breakpoint.smAndDown"
                >
                  New Task
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-form
                    ref="form"
                    @submit.prevent="save"
                    lazy-validation
                    v-model="valid"
                  >
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="12" md="12" lg="12">
                          <v-text-field
                            :label="labels['name']"
                            v-model="form.name"
                            :error-messages="errors['name']"
                            :rules="[rules.required('name')]"
                            :disabled="loading"
                            @input="clearErrors('name')"
                            autocomplete="off"
                          ></v-text-field>
                        </v-col>
                        <v-col>
                          <v-select
                            :items="statusList"
                            item-text="sttaus"
                            item-value="id"
                            label="Status"
                            v-model="form.status"
                            autocomplete="off"
                          ></v-select>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">
                    Cancel
                  </v-btn>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="save"
                    :disabled="loading"
                  >
                    Save
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="260px">
              <v-card>
                <v-card-title class="text-h5"
                  >Are you sure you want to delete?</v-card-title
                >
                <v-card-text>You won't be able to revert this!</v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" text @click="closeDelete"
                    >Cancel</v-btn
                  >
                  <v-btn color="green darken-1" text @click="deleteItemConfirm"
                    >Yes</v-btn
                  >
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon color="primary" small class="mr-2" @click="editItem(item)">
            mdi-pencil
          </v-icon>
          <v-icon color="error" small @click="deleteItem(item)">
            mdi-delete
          </v-icon>
        </template>
        <template v-slot:no-data>
          No Data Found
          <!-- <v-btn color="primary" @click="initialize"> Reset </v-btn> -->
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import Form from "~/mixins/form";

export default {
  mixins: [Form],
  name: "AllTasks",
  data: () => ({
    statusList: ["completed", "pending"],
    tasks: [],
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        text: "#",
        align: "start",
        sortable: true,
        value: "sno",
        class: "green lighten-2",
      },
      {
        text: "Name",
        align: "start",
        sortable: true,
        value: "name",
        class: "green lighten-2",
      },
      {
        text: "Status",
        align: "start",
        sortable: true,
        value: "status",
        class: "green lighten-2",
      },
      {
        text: "Actions",
        value: "actions",
        sortable: false,
        class: "green lighten-2",
      },
    ],
    editedIndex: -1,
    form: {
      name: null,
    },
    defaultItem: {
      name: null,
    },
  }),

  async created() {
    this.loading = true;
    await this.initialize();
    this.loading = false;
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Task" : "Edit Task";
    },
  },
  methods: {
    ...mapActions({
      getTasks: "task/getTasks",
      saveTask: "task/saveTask",
      updateTask: "task/updateTask",
      deleteTask: "task/deleteTask",
    }),
    async initialize() {
      this.tasks = await this.getTasks();
      console.log(this.tasks, "tasks");
      this.tasks = this.tasks.map((d, index) => ({ ...d, sno: index + 1 }));
    },
    editItem(item) {
      this.editedIndex = this.tasks.indexOf(item);
      this.form = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.tasks.indexOf(item);
      this.form = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      let response = await this.deleteTask(this.form);
      if (response.status == 200) {
        this.$toast.success(response.data.message);
      }
      this.closeDelete();
      await this.initialize();
    },

    close() {
      console.log("Ea");
      this.dialog = false;
      this.$nextTick(() => {
        this.form = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.form = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async save() {
      if (this.$refs.form.validate()) {
        this.loading = true;
        let response = null;
        if (this.editedIndex > -1) {
          response = await this.updateTask(this.form);
        } else {
          response = await this.saveTask(this.form);
        }
        console.log(response, "here");
        if (response.status == 200) {
          this.$toast.success(response.data.message);
        } else {
          this.handleErrors(response.data.errors);
        }
        await this.initialize();
        this.close();
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.v-dialog .v-dialog--active {
  max-height: 500px !important;
}
</style>