<template>
  <transition name="modal" appear>
    <div class="modal modal-overlay" @mousedown.self="$emit('close')">
      <div class="modal-window">
        <header class="modal-header">
          <slot name="head"></slot>
        </header>
        <div class="modal-content">
          <slot/>
        </div>
      </div>
    </div>
  </transition>
</template>

<style lang="stylus" scoped>
.modal {
  &.modal-overlay {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    z-index: 30;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
  }

  &-window {
    background: #fff;
    border-radius: 5px;
    overflow: hidden;
  }

  &-header {
    display: flex;
    height: 50px;
    margin: 0;
    padding: 0 20px;
  }

  &-content {
    padding: 20px 20px;
    border: 0px;
  }

  &-footer {
    padding: 10px;
    text-align: right;
  }
}

// オーバーレイのトランジション
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.4s;

  // オーバーレイに包含されているモーダルウィンドウのトランジション
  .modal-window {
    transition: opacity 0.4s, transform 0.4s;
  }
}

// ディレイを付けるとモーダルウィンドウが消えた後にオーバーレイが消える
.modal-leave-active {
  transition: opacity 0.6s ease 0.4s;
}

.modal-enter, .modal-leave-to {
  opacity: 0;

  .modal-window {
    opacity: 0;
    transform: translateY(-20px);
  }
}
</style>