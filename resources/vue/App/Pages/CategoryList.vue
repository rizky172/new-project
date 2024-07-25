<script>
export default {
    data: function() {
        return {
            list: []
        };
    },
  created: function() {},
  mounted: function() {},
  computed: {
        sortList: function() {
            var vm = this;

            return _.sortBy(vm.list, 'label');
        },
  },
  watch: {}
};
</script>

<template>
    <section class="content">
        <div class="container-fluid">
            <wv-row>
                <wv-col>
                    <wv-card>
                        <wv-row>
                            <wv-col class="mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Nama</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <template  v-for="(tr, $index) in sortList">
                                                <tr :key="$index" v-if="$ac.hasAccesses(tr.permission)">
                                                    <td>{{ parseInt($index) + 1 }}</td>
                                                    <td>
                                                        <router-link :to="{ name: tr.name }">
                                                            <i
                                                                class="far nav-icon fa fa-fw"
                                                                v-bind:class="[tr.icon ? tr.icon : 'fa-th']"></i>
                                                            <span class="ml-2">{{ tr.label }}</span>
                                                        </router-link>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </wv-col>
                        </wv-row>
                    </wv-card>
                </wv-col>
            </wv-row>
        </div>
    </section>
</template>
