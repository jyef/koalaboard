<template>
  <div>
    <!-- id="file"は同名ファイルをアップできるようにするためcloseModalメソッドで使用している -->
    <div class="wrap_label">
      <label>
        <div class="filelabel">
          <img
            v-if="cropImg !== ''"
            :src="cropImg"
            alt="Cropped Image"
            class="c_cropped_image"
          >
        <i v-if="cropImg === ''" class="fas fa-image imageicon fa-lg"></i>
        <input id="file" class="datafile" @change="setImage" type="file" accept="image/*">
        </div>
      </label>
      <i v-if="cropImg !== ''" @click="delcropImg" class="fas fa-times image_cancel_icon fa-lg"></i>
    </div>
    <input type="hidden" name="image" :value="cropImg">

    <!-- 画像アップロード時、エディット画面をモーダルで表示 -->
    <MyModal @close="closeModal" v-if="modal">
      <!-- エディット画面 -->
      <template v-slot:head>
        <div class="header-left">
          <i class="fas fa-arrow-left" @click="closeModal"></i>
        </div>
        <div class="header-center">
          <h3>Edit media</h3>
        </div>
        <div class="header-right">
          <a
            href="#"
            role="button"
            @click.prevent="cropImage"
            class="btn-primary rounded-pill"
          >
            Apply
          </a>
        </div>
      </template>
      <div
        v-if="imgSrc !== ''"
        class="l_cropper_container"
      >
        <vue-cropper
          ref="cropper"
          :guides="true"
          :view-mode="2"
          :auto-crop-area="0.8"
          :min-container-width="500"
          :min-container-height="500"
          :background="true"
          :rotatable="false"
          :src="imgSrc"
          :img-style="{ 'width': '500px', 'height': '500px' }"
          :aspect-ratio="targetWidth / targetHeight"
          drag-mode="crop"
        />
        <br>

            <a
              href="#"
              role="button"
              @click.prevent="cropImage"
            >
              Crop
            </a>
      </div>
    </MyModal>
  </div>
</template>

<script>
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'
import MyModal from './MyModal'
export default {
  components: {
    VueCropper,
    MyModal
  },
  props: {
    pictureBase64: {
      type: String,
      default: '',
    },
  },
  data () {
    return {
      targetWidth: 1,
      targetHeight: 1,
      imgSrc: '',
      cropImg: this.pictureBase64,
      filename: '',
      modal: false,
      message: ''
    }
  },
  methods: {
    // window:onload = function() {
    //   alert('koko');
    // },
    setImage (e) {
      const file = e.target.files[0]
      this.filename = file.name
      if (!file.type.includes('image/')) {
        alert('Please select an image file')
        return
      }
      if (typeof FileReader === 'function') {

        const reader = new FileReader()
        reader.onload = (event) => {
          this.imgSrc = event.target.result
          // onloadが発生した後にモーダルを表示しないと前回アップした画像が表示されてしまう
          this.openModal()
        }
        // この後にonloadが発生することに注意
        reader.readAsDataURL(file)
      } else {
        alert('Sorry, FileReader API not supported')
      }
    },
    cropImage () {
      this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL()
      this.closeModal()
    },
    delcropImg() {
      this.cropImg = ''
    },
    openModal() {
      this.modal = true
    },
    closeModal() {
      this.modal = false
      // モーダルを閉じる際にinput fileのファイルパスを削除(同名ファイルをアップできるように)
      document.getElementById("file").value = ''
    },
    doSend() {
      if (this.message.length > 0) {
        alert(this.message)
        this.message = ''
        this.closeModal()
      } else {
        alert('メッセージを入力してください')
      }
    }
  }
}
</script>

<style scoped>
.wrap_label {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100px;
  height: 100px;
  margin-bottom: 20px;
}
.filelabel {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100px;
  height: 100px;
  background-color: #aaa;
}
.c_cropped_image {
  width: auto;
  height: auto;
  max-width: 100%;
  max-height: 100%;
}
.filelabel:hover {
  cursor: pointer;
}
.imageicon {
  position: absolute;
  color: #eee;
}
.image_cancel_icon {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: #ccc;
  color: #666;
  opacity: 0.6;
}
.image_cancel_icon:hover {
  -webkit-box-shadow: 0 0 5px 0 #999;
  -moz-box-shadow: 0 0 5px 0 #999;
  box-shadow: 0 0 5px 0 #999;
  cursor: pointer;
  opacity: 1;
}
.datafile {
  display: none;
}
h2 {
  font-size: 25px;
  margin-top: 20px;
}
h3 {
  font-size: 20px;
  font-weight: bold;
}
.l_cropper_container {
  width: 500px;
  height: 500px;
  border: 1px solid gray;
  display: inline-block;
}
.header-left {
  flex-grow: 1;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  height: 100%;
}
.header-left .fa-arrow-left {
  color: rgba(29, 161, 242, 1);
  border-radius: 50%;
  padding: 10px;
}
.header-left .fa-arrow-left:hover {
  background-color: rgba(29, 161, 242, 0.1);
  cursor: pointer;
}
.header-center {
  flex-grow: 8;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  height: 100%;
}
.header-center h3 {
  margin: 0;
}
.header-right {
  flex-grow: 1;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  height: 100%;
}
.header-right a {
  font-size: 14px;
  font-weight: bold;
  padding: 5px 20px;
}
</style>