<template>
  <div class="row">
    <div class="col-12 mb-3">
      <div class="card bg-dark shadow">
        <div class="card-header">Create a new comment</div>
        <div class="card-body">
          <div>
            <b>Description</b>
            <textarea type="text" class="form-control" v-model="postComment.description"
                      placeholder="Description"></textarea>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <b class="d-block">File</b>
              <input type="file" class="d-none" ref="file" @change="onChangeFileUpload" multiple
                     accept="image/*">
              <button class="btn btn-outline-primary" @click="trigger" type="button">
                <i class="fa fa-upload"></i>
              </button>
            </div>
            <div class="col-6 text-right">
              <button class="btn btn-outline-primary mt-4" @click="send" type="button" :disabled="this.disabled">
                Save
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {actions} from "../api/apiForum";

export default {
  data() {
    return {
      files: [],
      filesToUpload: [],
      disabled: false
    }
  },
  watch: {
    'postComment.description': function (newVal, oldVal) {
      console.log('Why is it not working?');
    },
  },
  props: {
    postComment: {
      post: null,
      description: ''
    }
  },
  methods: {
    trigger() {
      this.$refs.file.click();
    },
    onChangeFileUpload() {
      this.files = this.$refs.file.files;
      for (let i = 0; i < this.files.length; i++) {
        this.filesToUpload.push(this.files[i]);
      }
    },
    send() {
      if (this.postComment.description) {
        actions.sendPostComment(this.postComment, this.files).then(() => {
          this.postComment = '';
        }).catch(error => {
          alert(error.response.data.error);
        });
      } else {
        alert('The description cannot be empty.');
      }
    }
  }
}
</script>