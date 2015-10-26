<?php

class WPBakeryShortCode_VC_Row_Inner extends WPBakeryShortCode_VC_Row {

	public function template( $content = '' ) {
		return $this->contentAdmin( $this->atts );
	}
}