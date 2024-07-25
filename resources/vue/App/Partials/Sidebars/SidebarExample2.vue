<script>
// import Sidebar from '../../Components/Sidebar.vue';

export default {
    props: {
        'title': String,
        'value': Object,
        'success': Function
    },
    components: {
//        Sidebar: Sidebar
    },
    data: function() {
        return {
            user: {}
        };
    },
    methods: {
        alert: function() {
            alert('test');
        },
        submit: function() {
            // console.log('submit');
            this.success();
        }
    }
}
</script>

<template>
    <sidebar title="Sidebar Example 2">
        <form action="#" role="form" v-on:submit.prevent="submit()">
          <div class="form-group">
            <label for="labelID">Label</label>
            <input type="text" class="form-control" id="labelID" placeholder="" v-model="value.label">
          </div>
          <div class="form-check pb-3">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
          </div>
          <div class="row pb-3">
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
          </div>
          <div class="form-group">
            <label for="labelID">Checkbox</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox">
              <label class="form-check-label">Checkbox</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" checked>
              <label class="form-check-label">Checkbox checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" disabled>
              <label class="form-check-label">Checkbox disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label for="">Radio</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1">
              <label class="form-check-label">Radio</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" checked>
              <label class="form-check-label">Radio checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" disabled>
              <label class="form-check-label">Radio disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label>Select</label>
            <select class="form-control">
              <option>option 1</option>
              <option>option 2</option>
              <option>option 3</option>
              <option>option 4</option>
              <option>option 5</option>
            </select>
          </div>
          <div class="form-group">
            <label for="customFile">Upload File</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
          </div>
          <div class="form-group">
            <label for="labelID">Label</label>
            <input type="text" class="form-control" id="labelID" placeholder="">
          </div>
          <div class="form-check pb-3">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
          </div>
          <div class="row pb-3">
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
          </div>
          <div class="form-group">
            <label for="labelID">Checkbox</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox">
              <label class="form-check-label">Checkbox</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" checked>
              <label class="form-check-label">Checkbox checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" disabled>
              <label class="form-check-label">Checkbox disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label for="">Radio</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1">
              <label class="form-check-label">Radio</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" checked>
              <label class="form-check-label">Radio checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" disabled>
              <label class="form-check-label">Radio disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label>Select</label>
            <select class="form-control">
              <option>option 1</option>
              <option>option 2</option>
              <option>option 3</option>
              <option>option 4</option>
              <option>option 5</option>
            </select>
          </div>
          <div class="form-group">
            <label for="customFile">Upload File</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
          </div>
          <div class="form-group">
            <label for="labelID">Label</label>
            <input type="text" class="form-control" id="labelID" placeholder="">
          </div>
          <div class="form-check pb-3">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
          </div>
          <div class="row pb-3">
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
          </div>
          <div class="form-group">
            <label for="labelID">Checkbox</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox">
              <label class="form-check-label">Checkbox</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" checked>
              <label class="form-check-label">Checkbox checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" disabled>
              <label class="form-check-label">Checkbox disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label for="">Radio</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1">
              <label class="form-check-label">Radio</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" checked>
              <label class="form-check-label">Radio checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" disabled>
              <label class="form-check-label">Radio disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label>Select</label>
            <select class="form-control">
              <option>option 1</option>
              <option>option 2</option>
              <option>option 3</option>
              <option>option 4</option>
              <option>option 5</option>
            </select>
          </div>
          <div class="form-group">
            <label for="customFile">Upload File</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
          </div>
          <div class="form-group">
            <label for="labelID">Label</label>
            <input type="text" class="form-control" id="labelID" placeholder="">
          </div>
          <div class="form-check pb-3">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
          </div>
          <div class="row pb-3">
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
            <div class="col-6">
              <input type="text" class="form-control" placeholder=".col-6">
            </div>
          </div>
          <div class="form-group">
            <label for="labelID">Checkbox</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox">
              <label class="form-check-label">Checkbox</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" checked>
              <label class="form-check-label">Checkbox checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" disabled>
              <label class="form-check-label">Checkbox disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label for="">Radio</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1">
              <label class="form-check-label">Radio</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="radio1" checked>
              <label class="form-check-label">Radio checked</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" disabled>
              <label class="form-check-label">Radio disabled</label>
            </div>
          </div>
          <div class="form-group">
            <label>Select</label>
            <select class="form-control">
              <option>option 1</option>
              <option>option 2</option>
              <option>option 3</option>
              <option>option 4</option>
              <option>option 5</option>
            </select>
          </div>
          <div class="form-group">
            <label for="customFile">Upload File</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
          </div>
          <button type="button" class="btn btn-primary" v-on:click="$sidebar.hide()">Close</button>
          <button type="submit" class="btn btn-primary">Submit XXX</button>
        </form>
    </sidebar>
</template>