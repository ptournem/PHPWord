<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/Ptournem/PHPWord
 * @copyright   2010-2014 Ptournem
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Template;


use PhpOffice\PhpWord\Exception\BlockAlreadyExistsException;
use PhpOffice\PhpWord\Exception\VariableAlreadyExistsException;

/**
 * Object use to describe a document's structure based on variables and blocks inside of it 
 */
class Block {

    /**
     *
     * @var Block[] 
     */
    private $innerBlocks;

    /**
     *
     * @var string[] 
     */
    private $variables;

    /**
     * Initialize properties 
     */
    public function __construct() {
	$this->innerBlocks = array();
	$this->variables = array();
    }

    /**
     * 
     * @return Block[]
     */
    public function getInnerBlocks() {
	return $this->innerBlocks;
    }

    /**
     * 
     * @return String[]
     */
    public function getVariables() {
	return $this->variables;
    }

    /**
     * 
     * Add an inner block contained in the block
     * @param string $blockKey
     * @param Block $block
     * @throws BlockAlreadyExistsException
     * @return boolean
     */
    public function addInnerBlocks($blockKey, $block) {
	if (array_key_exists($blockKey, $this->innerBlocks)) {
	    throw new BlockAlreadyExistsException($blockKey);
	}
	$this->innerBlocks[$blockKey] = $block;
	return true;
    }

    /**
     * * Add a variable contained in the block
     * @param string $variable
     * @return boolean
     * @throws VariableAlreadyExistsException
     */
    public function addVariables($variable) {
	if (in_array($variable, $this->variables)) {
	    throw new VariableAlreadyExistsException($variable);
	}

	if (!$this->is_in_block($variable)) {
	    $this->variables[] = $variable;
	    return true;
	}
    }

    /**
     * Remove a varible in the block
     * @param string $variable
     */
    public function removeVariable($variable) {
	if (($key = array_search($variable, $this->variables)) !== false) {
	    unset($this->variables[$key]);
	}
    }

    /**
     * Verify if a variable is in an innerblock 
     * @param String $variable
     * @return boolean
     */
    public function is_in_block($variable) {
	// look for the variable in the variable array
	if (in_array($variable, $this->variables)) {
	    // the variable is in this block ==> return true
	    return true;
	} else {
	    // look in each innerblock for the variable 
	    foreach ($this->innerBlocks as $block) {
		if ($block->is_in_block($variable)) {
		    return true;
		}
	    }
	}

	// the variable is not in a block ==> return false 
	return false;
    }

}
