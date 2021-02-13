<template>
  <div>
    <b-button v-b-modal.modal-add-post variant="primary">
      New Discussion
    </b-button>

    <b-modal id="modal-add-post" title="New Discussion" size="xl" header-bg-variant="main" body-bg-variant="tertiary"
             hide-footer content-class="shadow" hide-backdrop scrollable>
      <div class="row">
        <div class="col-lg-6 mb-3">
          <div class="card bg-dark shadow">
            <div class="card-header">Create a new post</div>
            <div class="card-body">
              <div>
                <b>Title</b>
                <input type="text" class="form-control" v-model="post.title" placeholder="Title"/>
              </div>
              <div>
                <b>Description</b>
                <textarea type="text" class="form-control" v-model="post.description"
                          placeholder="Description"></textarea>
              </div>
              <div>
                <div class="row">
                  <div class="col-lg-2">
                    <b class="d-block">File</b>
                    <input type="file" class="d-none" ref="file" @change="onChangeFileUpload" multiple
                           accept="image/*">
                    <button class="btn btn-outline-primary" @click="trigger" type="button">
                      <i class="fa fa-upload"></i>
                    </button>
                  </div>
                  <div class="col-lg-4">
                    <b class="d-block">Tags</b>
                    <div class="input-group">
                      <input class="form-control" type="text" placeholder="Tag" v-model="tag">
                      <div class="input-group-append">
                        <button class="btn btn-sm btn-outline-primary" @click="addTag" type="button">
                          <i class="fa fa-plus"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 text-right">
                    <button class="btn btn-outline-primary mt-4" @click="send" type="button"
                            :disabled="!this.post.title || !this.post.description">
                      Save
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 mb-3">
          <div class="card bg-dark shadow">
            <div class="card-header">
              <span class="badge badge-warning float-right">Preview post</span>
              {{ post.title }}
            </div>
            <div class="card-body">
              <p class="card-text">{{ post.description }}</p>

              <div>
                <img v-bind:src="showImage" v-for="showImage in showImages" height="200">
              </div>
            </div>
            <div class="card-footer" v-if="tags.length">
              <button v-for="(tag, index) in tags" type="button" class="btn btn-sm mx-1"
                      @click="removeTag(index)"
                      v-bind:class="{ 'badge-primary': index%2, 'badge-secondary': !(index%2) }">
                {{ tag }} <span class="badge badge-danger"><i class="fa fa-times"></i></span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import {actions} from "../api/apiForum";

export default {
  data() {
    return {
      tag: '',
      post: {
        title: '',
        description: ''
      },
      tags: [],
      files: [],
      filesToUpload: [],
      showImages: []
    }
  },
  mounted() {
    //
  },
  methods: {
    trigger() {
      this.$refs.file.click();
    },
    onChangeFileUpload() {
      this.files = this.$refs.file.files;
      for (let i = 0; i < this.files.length; i++) {
        this.filesToUpload.push(this.files[i]);
        this.createImage(this.files[i]);
      }
    },
    createImage(file) {
      this.showImages = [];
      const reader = new FileReader();

      reader.onload = (e) => {
        this.showImages.push(e.target.result);
      };
      reader.readAsDataURL(file);
    },
    addTag() {
      if (this.tag && !this.tags.includes(this.tag)) {
        this.tags.push(this.tag);
        this.tag = '';
      }
      if (this.tags.includes(this.tag)) {
        this.tag = '';
      }
    },
    removeTag(index) {
      this.tags.splice(index, 1);
    },
    removeImage(index) {
      this.filesToUpload.splice(index, 1);
      this.showImages.splice(index, 1);
    },
    send() {
      actions.sendPost(this.post, this.tags, this.filesToUpload);
      $('button.close').click();
    }
  }
}
</script>