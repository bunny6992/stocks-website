<template>
    <div :id="'treemap' + container" v-bind:style="{ height: height + 'px' , width: width + 'px' }">
        
    </div>
</template>

<script>
    export default {
        props: ['mapData', 'container', 'height', 'width'],
        data: function () {
            return {
                inst: {}
            }
        },
        mounted() {
            console.log(this.mapData)
            var data = [
              {parent: "Group 1", id: "alpha", value: 29},
              {parent: "Group 1", id: "beta", value: 10},
              {parent: "Group 1", id: "gamma", value: 2},
              {parent: "Group 2", id: "delta", value: 29},
              {parent: "Group 2", id: "eta", value: 25}
            ];
          // instantiate d3plus
          this.inst = new d3plus.Treemap();
          this.inst.data(this.mapData)
          .groupBy(["parent", "id"])
          .select("#treemap" + this.container)
          .shapeConfig({
    fill: function(d) {
      return d.color;
    }
  })
          .sum("value")
          .render(); 
        },

        watch: {
            height: function (newHeight) {
                this.inst._height = parseFloat(newHeight) - 50;
                this.inst.render();
            },
            width: function (newWidth) {
                this.inst._width = parseFloat(newWidth) - 50;
                this.inst.render();
            },
        }


    }
</script>
