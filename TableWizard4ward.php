<?php

/**
 * PHP version 5
 * @copyright  4ward.media 2013
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @package    table4ward
 * @license    LGPL 
 * @filesource
 */


class TableWizard4ward extends TableWizard
{

	public function generate(){
		$return = ' <a href="' . $this->addToUrl('key=table') . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['tw_import'][1]) . '" onclick="Backend.getScrollOffset()">' . $this->generateImage('tablewizard.gif', $GLOBALS['TL_LANG']['MSC']['tw_import'][0], 'style="vertical-align:text-bottom"') . '</a>';
		// Todo: rewrite resizing to TableWizard4ward
		// $return .= $this->generateImage('demagnify.gif', '', 'title="' . specialchars($GLOBALS['TL_LANG']['MSC']['tw_shrink']) . '" style="vertical-align:text-bottom;cursor:pointer" onclick="Backend.tableWizardResize(0.9)"') . $this->generateImage('magnify.gif', '', 'title="' . specialchars($GLOBALS['TL_LANG']['MSC']['tw_expand']) . '" style="vertical-align:text-bottom; cursor:pointer" onclick="Backend.tableWizardResize(1.1)"');
		$return .= ' <i>('. $GLOBALS['TL_LANG']['tl_content']['dblclickHint'].')</i><br><br>';
		
		$this->base = \Environment::get('base');
		
		// the javascript
		$GLOBALS['TL_JAVASCRIPT']['TableWizard4ward'] = 'system/modules/table4ward/html/TableWizard4ward.js';
		ob_start();
		include TL_ROOT.'/system/config/tinyTable4ward.php';
		$return .= ob_get_clean();
		
		// generate wizard
		$arrColButtons = array('cnew','ccopy', 'cmovel', 'cmover', 'cdelete');
		$arrRowButtons = array('rnew','rcopy', 'rup', 'rdown', 'rdelete');

		$strCommand = 'cmd_' . $this->strField;
		
		// load language if wizard is not used in tl_content
		if($this->strTable != 'tl_content') $this->loadLanguageFile('tl_content');

		// Change the order
		if ($this->Input->get($strCommand) && is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
		{
			$this->import('Database');

			switch ($this->Input->get($strCommand))
			{
				case 'cnew':
				case 'ccopy':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_duplicate($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'cmovel':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_move_up($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'cmover':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_move_down($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'cdelete':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_delete($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'rnew':
				case 'rcopy':
					$this->varValue = array_duplicate($this->varValue, $this->Input->get('cid'));
					break;

				case 'rup':
					$this->varValue = array_move_up($this->varValue, $this->Input->get('cid'));
					break;

				case 'rdown':
					$this->varValue = array_move_down($this->varValue, $this->Input->get('cid'));
					break;

				case 'rdelete':
					$this->varValue = array_delete($this->varValue, $this->Input->get('cid'));
					break;
			}

			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', $this->Environment->request)));
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || count($this->varValue) < 1)
		{
			$this->varValue = array(array(''));
		}

		// Begin table
		$return .= '<div id="tl_tablewizard">
  <table cellspacing="0" cellpadding="0" class="tl_tablewizard" id="ctrl_'.$this->strId.'" summary="Table wizard">
  <tbody>
    <tr>';

		// Add column buttons
		for ($i=0; $i<count($this->varValue[0]); $i++)
		{
			$return .= '
      <td style="text-align:center; white-space:nowrap;">';

			// Add column buttons
			foreach ($arrColButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&'.$strCommand.'='.$button.'&cid='.$i.'&id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['tl_content'][$button][1]).'" onclick="TableWizard4ward.tableWizard(this, \''.$button.'\', \'ctrl_'.$this->strId.'\'); return false;">'.$this->generateImage(substr($button, 1).'.gif', $GLOBALS['TL_LANG']['tl_content'][$button][0], 'class="tl_tablewizard_img"').'</a> ';
			}

			$return .= '</td>';
		}

		$return .= '
      <td></td>
    </tr>';

		$tabindex = 0;

		// Add rows
		for ($i=0; $i<count($this->varValue); $i++)
		{
			$return .= '
    <tr>';

			// Add cells
			for ($j=0; $j<count($this->varValue[$i]); $j++)
			{
				$return .= '
      <td class="tcontainer"><textarea name="'.$this->strId.'['.$i.']['.$j.']" class="tl_textarea noresize" tabindex="'.++$tabindex.'" rows="'.$this->intRows.'" cols="'.$this->intCols.'"'.$this->getAttributes().'>'.specialchars($this->varValue[$i][$j]).'</textarea></td>';
			}

			$return .= '
      <td style="white-space:nowrap;">';

			// Add row buttons
			foreach ($arrRowButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&'.$strCommand.'='.$button.'&cid='.$i.'&id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['tl_content'][$button][1]).'" onclick="TableWizard4ward.tableWizard(this, \''.$button.'\', \'ctrl_'.$this->strId.'\'); return false;">'.$this->generateImage(substr($button, 1).'.gif', $GLOBALS['TL_LANG']['tl_content'][$button][0], 'class="tl_tablewizard_img"').'</a> ';
			}

			$return .= '</td>
    </tr>';
		}

		$return .= '
  </tbody>
  </table>
  </div>';
	return $return;	
	}
	
}
