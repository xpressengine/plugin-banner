$.fn.extend({
  bannerEditor: function (options) {
    if (this.length === 0) {
      return this
    }

    // support multiple elements
    if (this.length > 1) {
      this.each(function () {
        $(this).bannerEditor(options)
      })
      return this
    }

    var self = this
    var ui = {
      itemList: self.find('.__xe_item_list'),
      addItemBtn: self.find('.__xe_add_item_btn'),
      itemEditor: self.find('.__xe_item_editor'),
      reloadBtn: self.find('.__xe_reload_btn')
    }
    self.ui = ui

    if (self.data('bannerEditor')) {
      return
    }

    this.locked = true
    this.lock = function () {
      if (this.locked) {
        return false
      }
      return this.locked = true
    }
    this.unlock = function () {
      this.locked = false
    }
    this.itemSaved = function (data) {
      var item = data.item
      var newItem = makeItem(item)
      var oldItem = ui.itemList.find('li[data-id=' + newItem.data('id') + ']')
      oldItem.before(newItem)
      oldItem.remove()
      selectItem(newItem.data('id'))
      if(data.alert){
        XE.toast('success',data.alert.message)
      }
    }
    this.reorder = function () {
      var orders = ui.itemList.sortable('toArray', {attribute: 'data-id'})
      var url = self.data('updateUrl')
      window.XE.ajax(url, {
        data: {orders: orders},
        type: 'put',
        dataType: 'json'
      })
    }

    // private functions
    var init = function () {
      if (self.data('bannerEditor')) {
        return
      }
      bind()

      ui.itemList.sortable({
        placeholder: 'well well-lg',
        update: $.proxy(self.reorder, self)
      })

      self.unlock()
    }

    var bind = function () {
      // click add btn
      ui.addItemBtn.click(function () {
        if (!self.lock()) {
          return false
        }
        var url = $(this).data('url')

        addItem(url)
      })

      // select item
      ui.itemList.on('click', 'li', function () {
        if (!self.lock()) {
          return false
        }
        selectItem($(this).data('id'))
      })

      // remove item
      ui.itemList.on('click', 'li .close', function (e) {
        if (!self.lock()) {
          return false
        }
        var li = $(this).parents('li')
        if (li.find('.selected:hidden').length === 0) {
          ui.itemEditor.empty()
        }
        li.hide()
        removeItem(li.data('deleteUrl'), function (data) { li.remove() })
        return false
      })

      // check timer
      ui.itemEditor.on('change', '.__xe_use_timer', function () {
        $('.__xe_timer_setting').slideToggle()
      })
    }

    var addItem = function (url) {
      window.XE.ajax(url, {
        type: 'POST',
        success: function (data) {
          itemAdded(data)
        }
      })
    }

    var itemAdded = function (data) {
      var item = makeItem(data.item)
      ui.itemList.prepend(item)
      selectItem(item.data('id'))
    }

    var removeItem = function (url, callback) {
      window.XE.ajax(url, {
        type: 'DELETE',
        success: function (data) {
          callback(data)
          self.unlock()
        }
      })
    }

    var selectItem = function (item_id) {
      var li = ui.itemList.find('li[data-id=' + item_id + ']')
      li.find('.title').addClass('text-primary')
      li.find('.selected').show()
      li.siblings().find('.title').removeClass('text-primary')
      li.siblings().find('.selected').hide()

      var edit_url = li.data('editUrl')

      resetItemEditor(edit_url)
    }

    var resetItemEditor = function (url) {
      renderItemEditor(url)
    }

    var renderItemEditor = function (url) {
      window.XE.page(url, '.__xe_item_editor', {}, $.proxy(self.unlock, self))
    }

    var makeItem = function (item) {
      return $($.parseHTML(item))
    }

    init()
    self.data('bannerEditor', this)
    return this
  }
})
