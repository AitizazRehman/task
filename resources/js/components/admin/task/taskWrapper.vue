<template>
  <v-container fluid grid-list-xl>
    <v-layout row justify-center wrap>
      <v-flex lg12 sm12 xs12>
        <transition name="fade" mode="out-in">
          <router-view></router-view>
        </transition>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import Sidemenu from "$comp/ui/Sidemenu";

export default {
  components: {
    Sidemenu,
  },

  data: () => ({
    items: [],
  }),

  mounted() {
    this.navigation();
  },

  methods: {
    navigation() {
      this.items = [
        { title: "View", icon: "visibility", to: { name: "profile" } },
        { title: "Edit", icon: "edit", to: { name: "profile-edit" } },
      ];
    },
  },
};
</script>
