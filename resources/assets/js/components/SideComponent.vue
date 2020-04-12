<template>
  <div class="col-4">
    <p class="small mb-0">{{ userName }}</p>
    <h4 class="mb-0">{{ teamName }}</h4>
    <table class="table table-sm">
      <thead>
        <tr :class="getClass(division)">
          <th class="p-0 text-center">D{{division}}</th>
          <th class="py-0 gate text-right">£{{gate}}</th>
        </tr>
      </thead>
    </table>    
  </div>
</template>

<script>
  export default {
    props: ['teamName', 'userName', 'division', 'gate'],
    methods: {
      getClass(division) {
        let myClass = "";
        if (division == 1) myClass="top-flight";
        if (division == 2) myClass="mid-tier";
        if (division == 3) myClass="lower-league";
        return myClass;
      }  
    }
  };
</script>