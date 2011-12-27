<?php
class Truncate{
	static protected $sentenceBoundaries = array('.','?','!');
	protected $wordComplete = true;
	protected $sentenceComplete = true;

	public function chars($string, $maxLength) {
		if (strlen($string) > $maxLength) {
			if (preg_match('/\w/',substr($string,$maxLength-1,1))==1)
				$this->wordComplete = false;
			if(!in_array(substr($string,$maxLength-2,1),self::$sentenceBoundaries))
				$this->sentenceComplete = false;
			
			$string = rtrim(substr($string,0,$maxLength));	
		}
		return $string;
	}

	public function sentences($string, $count = 0) {
		$b = preg_quote(implode('',self::$sentenceBoundaries));
		if($count > 0) {
			preg_match('/^([^'.$b.']+['.$b.']*\s*){0,'.$count.'}/', $string, $regs);
			$string = rtrim($regs[0]);
		} else if($count == 0) {
			if(!$this->sentenceComplete || !$this->wordComplete)
				return $this->sentences(-1);
			preg_match('/^([^'.$b.']+['.$b.']*\s*)*/', $string, $regs);
			$string = rtrim($regs[0]);
		} else {
			preg_match('/^([^'.$b.']+['.$b.']*\s*)*/', $string, $regs, PREG_OFFSET_CAPTURE);
			$string = rtrim(substr($string, 0, $regs[count($regs)+$count][1]));
		}
		$this->sentenceComplete = true;
		$this->wordComplete = true;
		return $string;
	}

	public function words($string, $count = 0) {
		if($count > 0) {
			preg_match('/^(\S+\s*){0,'.$count.'}/', $string, $regs);
			$string = rtrim($regs[0]);
		} else if($count == 0) {
			if(!$this->wordComplete)
				return $this->words(-1);
			preg_match('/^(\S+\s*)*/', $string, $regs);
			$string = rtrim($regs[0]);
		} else {
			preg_match('/^(\S+\s*)*/', $string, $regs, PREG_OFFSET_CAPTURE);
			$string = rtrim(substr($string, 0, $regs[count($regs)+$count][1]));
		}
		$this->wordComplete = true;
		return $string;
	}

	public function fixHTML($string) {
		// Remove half complete HTML from end of string
		$string = rtrim(preg_replace('/(?:&\w*|<[^>]*)$/', '', $string));
		// Check for unclosed tags
		if(preg_match_all("/<(\/?([a-z]+)(?:\s[^>]+)?)>/i", $string, $matches)) {
			$tags = new SplStack();
			foreach($matches[1] as $id => $tag) {
				if(substr($tag,-1,1) == '/') {
					//self closing. ignored.
					continue;
				} else if(substr($tag,0,1) == '/') {
					//tag close. remove from stack
					if($tags->top() != $matches[2][$id])
						throw new RuntimeException('Screwed xhtml is screwed.');
					$tags->pop();
				} else {
					//tag open. add to stack
					$tags->push($matches[2][$id]);
				}
			}
			if($tags->count()>0){
				foreach($tags AS $tag) {
					$string .= '</' . $tag . '>';
				}
			}
		}
		return $string;
	}
}