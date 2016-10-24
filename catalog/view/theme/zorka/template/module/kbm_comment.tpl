<?php
$kuler = Kuler::getInstance();
$kuler->language->load('kuler/zorka');
?>
<div class="comment-stats zorka">
  <?php echo _t('text_x_comments', $comment_total); ?> (<?php echo _t('text_x_replies', $reply_total); ?>)
</div>

<ol class="comment-list">
  <?php for ($i = 0; $i < count($comments); $i++) { ?>
    <?php $comment = $comments[$i]; ?>
    <li id="comment-<?php echo $comment['comment_id']; ?>" class="comment <?php echo ($i % 2) ? 'odd' : 'even'; ?>" itemprop="comment" itemscope="" itemtype="http://schema.org/UserComments">
      <img src="<?php echo $comment['author']['avatar_url']; ?>" class="avatar" />
      <div class="author">
        <span class="author-name" itemprop="creator"><?php echo $comment['author']['name']; ?></span>
        <?php if ($comment['author']['group']) { ?>
          <span class="author-badge<?php if ($comment['author']['badge_color']) echo ' author-badge-color'; ?>"<?php if ($comment['author']['badge_color']) echo ' style="background: ' . $comment['author']['badge_color'] . '"' ?>><?php echo $comment['author']['group']; ?></span>
        <?php } ?>
      </div>
      <div class="extra-info">
        <div class="date" itemprop="commentTime"><?php echo $comment['date_added_formatted']; ?></div>
        <div class="reply"><a data-comment-id="<?php echo $comment['comment_id']; ?>"><?php echo _t('button_reply'); ?></a></div>
      </div>
      <p class="message" itemprop="commentText"><?php echo $comment['content']; ?></p>
      <?php if ($comment['reply_total']) { ?>
        <ol class="comment-list reply-list">
          <?php for ($j = 0; $j < count($comment['replies']); $j++) { ?>
            <?php $reply = $comment['replies'][$j]; ?>
            <li class="comment <?php echo ($j % 2) ? 'odd' : 'even'; ?>">
              <img src="<?php echo $reply['author']['avatar_url']; ?>" class="avatar" />
              <div class="author">
                <span class="author-name" itemprop="creator"><?php echo $reply['author']['name']; ?></span>
                <?php if ($reply['author']['group']) { ?>
                  <span class="author-badge<?php if ($reply['author']['badge_color']) echo ' author-badge-color'; ?>"<?php if ($reply['author']['badge_color']) echo ' style="background: ' . $reply['author']['badge_color'] . '"' ?>><?php echo $reply['author']['group']; ?></span>
                <?php } ?>
              </div>
              <div class="date" itemprop="commentTime"><?php echo $reply['date_added_formatted']; ?></div>
              <p class="message" itemprop="commentText"><?php echo $reply['content']; ?></p>
            </li>
          <?php } ?>
        </ol>
      <?php } ?>
    </li>
  <?php } ?>
</ol>

<?php if ($pagination) { ?>
  <div class="pagination">
    <?php echo $pagination; ?>
  </div>
<?php } ?>

<div id="comment-form-container">
  <div id="comment-form">
    <h4 id="comment-form__title" class="row-heading" data-text-reply="<?php echo _t('text_leave_a_reply'); ?>" data-text-comment="<?php echo _t('text_leave_a_comment'); ?>"><?php echo _t('text_leave_a_comment'); ?></h4>
    <form action="?" id="form-comment" class="col-xs-10 no-padding-xs form-horizontal">
      <input type="hidden" name="kbm_article_id" value="<?php echo $article_id; ?>" />
      <input type="hidden" name="parent_comment_id" />
      <div class="form-group required">
        <label for="inputName" class="col-sm-3 control-label"><?php echo _t('entry_name'); ?></label>
        <div class="col-sm-9 validator" id="validator-name">
          <input type="text" class="form-control" id="inputName" name="name" value="<?php echo $comment_author['name']; ?>" />
        </div>
      </div>
      <div class="form-group required">
        <label for="inputEmail" class="col-sm-3 control-label"><?php echo _t('entry_email'); ?></label>
        <div class="col-sm-9 validator" id="validator-email">
          <input type="email" class="form-control" id="inputEmail" name="email" value="<?php echo $comment_author['email']; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="inputWebsite" class="col-sm-3 control-label"><?php echo _t('entry_website'); ?></label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="inputWebsite" name="website" value="<?php echo $comment_author['website']; ?>" />
        </div>
      </div>
      <div class="form-group required">
        <label for="txtComment" class="col-sm-3 control-label"><?php echo _t('entry_comment'); ?></label>
        <div class="col-sm-9 validator" id="validator-content">
          <textarea class="form-control" id="txtComment" name="content" cols="60" rows="5"></textarea>
        </div>
      </div>
      <?php if (isset($captcha_url)) { ?>
        <div class="form-group">
          <label for="inputCaptcha" class="col-sm-3 control-label"><?php echo _t('entry_captcha'); ?></label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="inputCaptcha" name="captcha" />
            <img src="<?php echo $captcha_url; ?>" id="captcha" />
          </div>
        </div>
      <?php } ?>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-10">
          <button type="submit" id="comment-submit" class="btn"><span><?php echo $kuler->language->get('text_submit_comment'); ?></span></button>
          <a id="reply-cancel" class="btn"><span><?php echo _t('button_cancel_reply'); ?></span></a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  var CommentWritingUrl = 'index.php?route=module/kbm/writeComment';

  var CommentForm = (function () {
    return {
      init: function () {
        var commentForm = this;

        commentForm.$commentFormContainer = $('#comment-form-container');
        commentForm.$commentForm = $('#comment-form');
        commentForm.$form = commentForm.$commentForm.find('#form-comment');
        commentForm.$commentFormTitle = commentForm.$commentForm.find('#comment-form-title');
        commentForm.$parentCommentId = commentForm.$commentForm.find('input[name="parent_comment_id"]');
        commentForm.$replyCancel = commentForm.$commentForm.find('#reply-cancel');
        commentForm.$submit = commentForm.$commentForm.find('#comment-submit');
        commentForm.$validator = commentForm.$commentForm.find('.validator');

        commentForm.commentId = 0;

        commentForm.bindEvents();
        commentForm.$replyCancel.hide();
      },
      bindEvents: function () {
        var commentForm = this;

        commentForm.$replyCancel.on('click', function (evt) {
          evt.preventDefault();
          commentForm.renderCommentForm();
        });

        commentForm.$form.on('submit', function (evt) {
          evt.preventDefault();

          $.ajax({
            url: CommentWritingUrl,
            type: 'POST',
            dataType: 'json',
            data: commentForm.$form.serialize(),
            beforeSend: function () {
              commentForm.$validator.find('.blog-field-error').remove();
              commentForm.$submit.before(Kuler.waitHtml);
              commentForm.$commentForm.find('.warning').remove();
            },
            complete: function () {
              $('.waiting').remove();
            },
            success: function (response) {
              if (response.status) {
                CommentList.renderPage(1);
              } else {
                commentForm.$commentForm.prepend('<div class="warning">'+ response.message +'</div>');

                if (response.errors) {
                  for (var field in response.errors) {
                    commentForm.$validator.filter('#validator-' + field).append('<p class="blog-field-error">'+ response.errors[field] +'</p>');
                  }
                }
              }
            }
          });
        });
      },
      renderReplyForm: function (commentId, $reply) {
        Comment.showReplyButton(this.commentId);

        this.commentId = commentId;

        this.$commentFormTitle.text(this.$commentFormTitle.data('textReply'));

        this.$replyCancel.show();
        this.$parentCommentId.val(commentId);

        $reply.after(this.$commentForm);
      },
      renderCommentForm: function () {
        Comment.showReplyButton(this.commentId);
        this.commentId = 0;
        this.$parentCommentId.val(0);

        this.$commentFormTitle.text(this.$commentFormTitle.data('textComment'));

        this.$replyCancel.hide();
        this.$commentFormContainer.append(this.$commentForm);
      }
    };
  })();

  var Comment = (function () {
    return {
      init: function () {
        $('.reply a').on('click', function (evt) {
          evt.preventDefault();

          var $replyBtn = $(this),
            $parent = $replyBtn.parent();
          $message = $replyBtn.parent().parent().next();

          $parent.hide();
          CommentForm.renderReplyForm($replyBtn.data('commentId'), $message);
        });
      },
      showReplyButton: function (commentId) {
        $('#comment-' + commentId + ' .reply').show();
      }
    };
  })();

  var Pagination = (function () {
    return {
      init: function () {
        $('.pagination a').on('click', function (evt) {
          evt.preventDefault();

          var match = this.href.match(/page=(\d+)/);

          if (match) {
            CommentList.renderPage(match[1]);
          }
        });
      }
    };
  })();

  Comment.init();
  CommentForm.init();
  Pagination.init();
</script>