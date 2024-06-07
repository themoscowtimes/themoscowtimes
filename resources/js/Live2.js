define('Live')
  .use('yellow.View')
  .as(function(y, View) {
    var _scope;
    // Keep track of all loaded ids
    var _ids = {};
    // Keep track of latest loaded timstamp
    var _from = 0;
    // Queue of older posts
    var _more = [];
    // Amount of posts to show when scrolling down
    var _show = 5;

    // Cached views
    var _postView
    var _blockHtmlView
    var _blockArticleView
    var _blockLinkView
    var _blockImageView
    var _blockEmbedView


    this.start = function(scope) {

      _scope = scope;

      // Create reusable views
      _postView = View.make(_scope.template('post'), {}, {
        time: function(time) {
          var months = ['Jan.', 'Feb.', 'March', 'April', 'May', 'June', 'July', 'Aug.', 'Sept.', 'Oct.', 'Nov.', ' Dec.'];
          var date = new Date(time);
          var hours = date.getHours();
          var ampm = hours >= 12 ? 'PM' : 'AM';
          hours = hours % 12;
          hours = hours ? hours : 12;
          return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear() + ' - ' + hours + ':' + String(date.getMinutes()).padStart(2, '0') + ' ' + ampm
        }
      });
      _blockHtmlView = View.make(_scope.template('block-html'));
      _blockArticleView = View.make(_scope.template('block-article'));
      _blockLinkView = View.make(_scope.template('block-link'));
      _blockImageView = View.make(_scope.template('block-image'));
      _blockEmbedView = View.make(_scope.template('block-embed'));

      // Start loading every 10 seconds
      setInterval(function() {
        load()
      }, 10 * 1000)


      y(window).scroll(function() {

        var elementBottom = scope.offset().top + scope.outerHeight();
        var viewportBottom = y(window).scrollTop() + y(window).height();
        if (elementBottom < viewportBottom) {
          more();
        }
      })


      // Start first load
      load();
    }


    var load = function() {
      // Whether it's the first batch or a later one
      var active = _from > 0;

      // Round from to a minute to get cached results by minute instead of by second
      var from = Math.floor(_from / 60) * 60;

      // Load new posts
      y.ajax(_scope.data('url').replace('{{from}}', from), {
        dataType: 'json'
      }).done(function(items) {
        // Loaded posts
        var loaded = [];
        for (var i = 0; i < items.length; i++) {
          var item = items[i];
          if (!_ids[item.id]) {
            // Flag id as loaded
            _ids[item.id] = true;
            // Update the from time
            _from = new Date(item.time).getTime() / 1000;
            // Store the loaded item
            loaded.push(item)
          }
        }

        if (active) {
          // Newly loaded articles: display all right now
          for (var i = 0; i < loaded.length; i++) {
            // pass true to render at the top with active flag
            render(loaded[i], true);
          }
        } else {
          // first batch: start lazyloading for this set
          _more = loaded;
          more();
        }
      })
    }


    var more = function() {
      // display x articles at the bottom
      // _more holds posts old to new, so get them from the back of the array
      for (var i = 0; i < _show; i++) {
        var item;
        if (item = _more.pop()) {
          // pass false to render at the bottom
          render(item, false);
        }
      }
    }


    var render = function(item, active) {
      var post = _postView.element(item);
      if (active) {
        post.addClass('live-post--new')
        _scope.prepend(post);
      } else {
        _scope.append(post);
      }

      var mount = post.fetch('body');
      for (var j = 0; j < item.body.length; j++) {
        var block = item.body[j];
        var element = null;
        if (block.type == 'html' && block.body) {
          element = _blockHtmlView.element();
          element.append(y(block.body))
        } else if (block.type == 'article' && block.article) {
          element = _blockArticleView.element(block.article);
        } else if (block.type == 'image' && block.image) {
          element = _blockImageView.element(block.image);
        } else if (block.type == 'link' && block.link) {
          element = _blockLinkView.element(block);
        } else if (block.type == 'embed' && block.embed) {
          element = y(_blockEmbedView.render());
          element.fetch('embed').append(y(block.embed))

        }

        if (element) {
          mount.append(element)
          if (block.type == 'embed') {
            element.start();
          }
        }
      }
    }



  });