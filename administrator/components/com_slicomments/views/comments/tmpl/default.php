<?php
// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';
require_once JPATH_COMPONENT.'/helpers/comments.php';
JHtml::_('core');
JHtml::_('behavior.framework', true);
JHtml::_('script', 'slicomments/comments_admin.js', true, true);
JHtml::_('script', 'slicomments/DynamicTextarea.js', true, true);
JHtml::_('stylesheet', 'slicomments/admin.css', array(), true);
$user		= JFactory::getUser();
$token		= '&'.JUtility::getToken().'=1';
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_slicomments');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-select fltrt">
			<select name="filter_status" id="filter_status" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', sliCommentsHelper::statusOptions() , 'value', 'text', $this->state->get('filter.status'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"></div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_COMMENTS_HEADING_AUTHOR', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JText::_('COM_COMMENTS_HEADING_COMMENT'); ?>
				</th>
				<th width="15%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_COMMENTS_HEADING_ARTICLE', 'a.article_id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php echo $this->escape($item->name); ?>
				</td>
				<td class="comment">
					<span class="submitted">Submitted on: <?php echo JHtml::_('date', $item->created, 'l, d F Y H:i:s');?></span>
					<span class="text"><?php echo $this->escape($item->text); ?></span>
					<ul class="actions">
						<?php if ($item->status == 1): ?>
							<li><a href="index.php?option=com_slicomments&amp;task=comments.unapprove&amp;cid[]=<?php echo $item->id.$token; ?>" class="unapprove-comment"><?php echo JText::_('COM_COMMENTS_ACTION_UNAPPROVE'); ?></a></li>
						<?php elseif ($item->status == 0) :?>
							<li><a href="index.php?option=com_slicomments&amp;task=comments.approve&amp;cid[]=<?php echo $item->id.$token; ?>" class="approve-comment"><?php echo JText::_('COM_COMMENTS_ACTION_APPROVE'); ?></a></li>
						<?php elseif ($item->status == -1) :?>
							<li><a href="index.php?option=com_slicomments&amp;task=comments.approve&amp;cid[]=<?php echo $item->id.$token; ?>" class="approve-comment"><?php echo JText::_('COM_COMMENTS_ACTION_NOT_SPAM'); ?></a></li>
						<?php elseif ($item->status == -2) :?>
							<li><a href="index.php?option=com_slicomments&amp;task=comments.approve&amp;cid[]=<?php echo $item->id.$token; ?>" class="approve-comment"><?php echo JText::_('COM_COMMENTS_ACTION_RESTORE'); ?></a></li>
						<?php endif; ?>

						<?php if ($item->status != -2) :?>
							<li><a href="#" class="edit-comment"><?php echo JText::_('COM_COMMENTS_ACTION_EDIT'); ?></a></li>
						<?php endif; ?>

						<?php if ($item->status >= 0) :?>
							<li><a href="index.php?option=com_slicomments&amp;task=comments.spam&amp;cid[]=<?php echo $item->id.$token; ?>" class="spam-comment"><?php echo JText::_('COM_COMMENTS_ACTION_SPAM'); ?></a></li>
							<li><a href="index.php?option=com_slicomments&amp;task=comments.trash&amp;cid[]=<?php echo $item->id.$token; ?>" class="trash-comment"><?php echo JText::_('COM_COMMENTS_ACTION_TRASH'); ?></a></li>
						<?php else: ?>
							<li><a href="index.php?option=com_slicomments&amp;task=comments.delete&amp;cid[]=<?php echo $item->id.$token; ?>" class="delete-comment"><?php echo JText::_('COM_COMMENTS_ACTION_DELETE_PERMANENTLY'); ?></a></li>
						<?php endif; ?>	
					</ul>
				</td>
				<td>
					<a href="../<?php echo ContentHelperRoute::getArticleRoute($item->alias ? ($item->article_id . ':' . $item->alias) : $item->article_id, $item->catid); ?>">
						<?php echo $this->escape($item->title); ?>
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<p class="copyright">sliComments is free software released under the <a href="http://www.gnu.org/licenses/gpl-3.0.html">GNU General Public License</a>. Icons by <a href="http://dryicons.com">DryIcons</a>.</p>

	<input type="hidden" name="controller" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
