<?php

if (!defined('ABSPATH'))
    exit;

class ACOPLW_Badge
{

    /**
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;
    public $product_lists = false;
    public $product_schedule_onsale = false;
    public $products_on_sale = false;
    public $customStyles = false;
    public $acoplwBadges = [];
    public $pScheduleStatus = [];
    private $_active = false;
    private $active_badges = false;

    public function __construct()
    {

        // $this->types = Array(
        //     'percent_total_amount' => __('Percentage of cart total amount', 'aco-product-labels-for-woocommerce'),
        //     'percent_product_price' => __('Percentage of product price', 'aco-product-labels-for-woocommerce'),
        //     'fixed_product_price' => __('Fixed price of product price', 'aco-product-labels-for-woocommerce'),
        //     'fixed_cart_amount' => __('Fixed price of cart total amount', 'aco-product-labels-for-woocommerce'),
        //     'cart_quantity' => __('Quantity based badge', 'aco-product-labels-for-woocommerce')
        // );

    }

    /**
     *
     * Ensures only one instance of ACOPLW is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WordPress_Plugin_Template()
     * @return Main ACOPLW instance
     */
    public static function instance($file = '', $version = '1.0.0')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->_active;
    }

    // Badges
    public function acoplwBadge ( $productThumb, $product, $textstatus = false ) {

        global $post;
        if ( is_a ( $product, 'WC_Product' ) ) {
            $productID = $product->get_ID();
        } elseif ( false === $product && isset( $post->ID ) ) {
            $productID = $post->ID;
        } else {
            $productID = $product;
        }

        // Load active badges
        $this->load_badges(); 

        if ( $this->active_badges == null )
            return ( !$textstatus ) ? $productThumb : '';

        foreach ( $this->active_badges as $k => $badge ) {  

            $badgeID = $badge['id'];

            // Get Product List
            if ( !$this->check_in_product_list ( $productID, $badgeID ) ) {
                continue;
            }

            if ( $this->pScheduleStatus[$badgeID] ) {
                if ( !$this->onSaleScheduleList( $productID, $badgeID ) ){
                    continue;
                }
            }

            $this->acoplwSaleBadge ( $productThumb, $productID, $badgeID );

        }
        
        // Get all badges
        $acoplwActiveBadges = array_key_exists ( $productID, $this->acoplwBadges ) ? $this->acoplwBadges[$productID] : ''; 
        if ( $acoplwActiveBadges ) {
            $badge = '';
            foreach ( $acoplwActiveBadges as $acoplwActiveBadge ) {
                $badge = $badge . $acoplwActiveBadge;
            }
            $productThumb = $textstatus ? '<span class="acoplw-badge acoplw-textBlock">' . $badge . '</span>'  : '<span class="acoplw-badge">' . $badge . $productThumb . '</span>';
        }
        
        // Return
        return $productThumb;

    }

    // Badge Detail 
    public function acoplwBadgeDetail () { 

        global $post;
        $productID      = $post->ID;
        $productThumb   = '';
        $textstatus     = true;
        $hiddenWrap     = 'acoplw-hidden-wrap';

        // Load active badges
        $this->load_badges(); 

        if ( $this->active_badges == null )
            return ( !$textstatus ) ? $productThumb : '';

        foreach ( $this->active_badges as $k => $badge ) {  

            $badgeID = $badge['id'];

            // Get Product List
            if ( !$this->check_in_product_list ( $productID, $badgeID ) ) {
                continue;
            }

            if ( $this->pScheduleStatus[$badgeID] ) {
                if ( !$this->onSaleScheduleList( $productID, $badgeID ) ){
                    continue;
                }
            }

            $this->acoplwSaleBadge ( $productThumb, $productID, $badgeID );

        }
     
        // Get all badges
        $acoplwActiveBadges = array_key_exists ( $productID, $this->acoplwBadges ) ? $this->acoplwBadges[$productID] : ''; 
        if ( $acoplwActiveBadges ) {   
            $badge = '';
            foreach ( $acoplwActiveBadges as $acoplwActiveBadge ) {
                $badge = $badge . $acoplwActiveBadge;
            } 
            $productThumb = $textstatus ? '<div class="'.$hiddenWrap.'"><span class="acoplw-badge acoplw-textBlock">' . $badge . '</span></div>' : '<div class="'.$hiddenWrap.'"><span class="acoplw-badge">' . $badge . $productThumb . '</span></div>';
        }  

        echo $productThumb;

    }

    // Show Badge
    public function acoplwSaleBadge ( $productThumb, $productID, $badgeID ) {

        if ( $this->active_badges != false && sizeof($this->active_badges) >= 1 && !is_cart() && !is_checkout() ) { 

            $customStyle                = '';

            $badgeOptions               = $this->active_badges; 
            $badgeOptions               = $badgeOptions[$badgeID]; 

            $label                      = $badgeOptions['label'];
            $labelColor                 = $badgeOptions['labelColor'];
            $fontSize                   = $badgeOptions['fontSize'];
            $lineHeight                 = $badgeOptions['lineHeight'];

            $badgeStyle                 = $badgeOptions['badgeStyle'];
            $badgeColor                 = $badgeOptions['badgeColor'];
            $badgeWidth                 = (int)$badgeOptions['badgeWidth'];
            $badgeHeight                = $badgeOptions['badgeHeight'];
            $borderTopLeft              = $badgeOptions['borderTopLeft'];
            $borderTopRight             = $badgeOptions['borderTopRight'];
            $borderBottomLeft           = $badgeOptions['borderBottomLeft'];
            $borderBottomRight          = $badgeOptions['borderBottomRight'];

            $opacity                    = $badgeOptions['opacity'];
            $rotationX                  = $badgeOptions['rotationX'];
            $rotationY                  = $badgeOptions['rotationY'];
            $rotationZ                  = $badgeOptions['rotationZ'];
            $flipHorizontal             = $badgeOptions['flipHorizontal'];
            $flipVertical               = $badgeOptions['flipVertical'];
            $badgePosition              = $badgeOptions['badgePosition'];
            $badgePositionHorizontal    = $badgeOptions['badgePositionHorizontal'];
            $posTop                     = $badgeOptions['posTop'];
            $posBottom                  = $badgeOptions['posBottom'];
            $posLeft                    = $badgeOptions['posLeft'];
            $posRight                   = $badgeOptions['posRight'];

            $CalcSixVal                 = ( $badgeWidth <= 60 ) ? 0.167 : ( ( $badgeWidth <= 90 ) ? 0.22 : ( ( $badgeWidth > 90 ) ? 0.25 : 0 ) );

            $CalcFiveValOne             = ( $badgeWidth < 85 ) ? 1.5 : ( ( $badgeWidth > 85 ) ? 1.41 : 0 );
            $CalcFiveValTwo             = ( $badgeWidth <= 40 ) ? 0 : ( ( $badgeWidth < 60 ) ? 0.11 : ( ( $badgeWidth < 85 ) ? 0.167 : ( ( $badgeWidth > 85 ) ? 0.26 : 0 ) ) );
            $CalcFiveValThree           = ( $badgeWidth <= 40 ) ? 0.45 : ( ( $badgeWidth < 60 ) ? 0.43 : ( ( $badgeWidth < 85 ) ? 0.42 : ( ( $badgeWidth > 85 ) ? 0.31 : 0 ) ) );

            $bsSixWidth                 = $badgeWidth != '' ? $badgeWidth + 30 : 90;

            $bsSixTop                   = $badgeWidth != '' ? $badgeWidth * $CalcSixVal : 15;

            $bsFiveWidth                = $badgeWidth != '' ? $badgeWidth * $CalcFiveValOne : 100; 
            $bsFiveTop                  = $badgeWidth != '' ? $badgeWidth * $CalcFiveValTwo : '';
            $bsFiveLeft                 = ( $badgeWidth != '' && $badgePositionHorizontal == 'bpthree' ) ? -$badgeWidth * $CalcFiveValThree . "px" : 'auto';
            $bsFiveRight                = ( $badgeWidth != '' && $badgePositionHorizontal == 'bpfour' ) ? -$badgeWidth * $CalcFiveValThree . "px" : 'auto';

            // $textcss = "color:rgba(".$labelColor['r'].", ".$labelColor['g'].", ".$labelColor['b'].", ".$labelColor['a'].");font-size:".$fontSize."px;line-height:".$lineHeight."px;";
            $textcss = "color:rgba(".$labelColor['r'].", ".$labelColor['g'].", ".$labelColor['b'].", ".$labelColor['a'].");";
            $textcss .= ( $flipHorizontal && $flipHorizontal ) ? 'transform: scaleX(-1) scaleY(-1);' : ( ( $flipHorizontal ) ? 'transform: scaleX(-1);' : ( ( $flipHorizontal ) ? 'transform: scaleY(-1);' : '' ) );
            $textcss .= ( $badgeStyle == 'bsfive' ) ? ( "background:rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a'].");width:" . $bsFiveWidth . "px;top:" . $bsFiveTop . "px;left:" . $bsFiveLeft . ";right:" . $bsFiveRight . ";" ) : '';
            $textcss .= ( $badgeStyle == 'bssix' ) ? ( "width:" . $bsSixWidth . "px;top:" . $bsSixTop . "px" ) : '';

            $css = "opacity:".($opacity / 100).";width:".$badgeWidth."px;font-size:".$fontSize."px;line-height:".$lineHeight."px;";
            $css .= ( $badgeStyle == 'bsone' && $badgeHeight ) ? "height:".$badgeHeight."px;" : ( ( $badgeStyle == 'bseight' && $badgeWidth ) ? "height:".$badgeWidth."px;" : '' );
            $css .= "transform:rotateX(". ( $rotationX * 3.6 )."deg) rotateY(". ( $rotationY * 3.6 ) ."deg) rotateZ(". ( $rotationZ * 3.6 ) ."deg);";
            $css .= ( $badgeStyle != 'bseight' ) ? "border-radius: ".$borderTopLeft."px ".$borderTopRight."px ".$borderBottomLeft."px ".$borderBottomRight."px;" : '';
            $css .= ( $posTop && $badgePosition != 'bptwo' ) ? "top:".$posTop."px;bottom:auto;" : ( ( $badgePosition == 'bpone' ) ? ( $posTop ? "top:".$posTop."px;bottom:auto;" : "top:0px;bottom:auto;" ) : '' );
            $css .= ( $posBottom && $badgePosition != 'bpone' ) ? "bottom:".$posBottom."px;top:auto;" : ( ( $badgePosition == 'bptwo' ) ? ( $posBottom ? "bottom:".$posTop."px;top:auto;" : "bottom:0px;top:auto;" ) : '' );
            $css .= ( $posLeft && $badgePositionHorizontal != 'bpfour' ) ? "left:".$posLeft."px;" : '';
            $css .= ( $posRight && $badgePositionHorizontal != 'bpthree' ) ? "right:".$posRight."px;" : '';
            $css .= ( $badgeStyle == 'bsfive' || $badgeStyle == 'bssix' ) ? "height:".$badgeWidth."px;" : "background:rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a'].");" ;

            $blockonecss = ( $badgeStyle == 'bssix' ) ? ( ( $badgePositionHorizontal == 'bpthree' ) ? ( "border-right: none; border-left: ".$badgeWidth."px solid rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a']."); border-bottom: ".$badgeWidth."px solid transparent;" ) : ( "border-left: none; border-right: ".$badgeWidth."px solid rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a']."); border-bottom: ".$badgeWidth."px solid transparent;") ) : '' ;

            $customClass = "acoplw-".get_post_field( 'post_name', $badgeID )."-custom";
            if ( $badgeStyle == 'bstwo' ) {
                $customStyle = ".".$customClass.":after { background:rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a'].") !important; }";
            } else if ( $badgeStyle == 'bsthree' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $customStyle = ".".$customClass.":before { border-left: 15px solid rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a'].") !important; border-right: none; }";
                } else {
                    $customStyle = ".".$customClass.":before { border-right: 15px solid rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a'].") !important; border-left: none; }";
                }
            } else if ( $badgeStyle == 'bsfour' ) {
                $customStyle = ".".$customClass.":before { border-color:rgba(".$badgeColor['r'].", ".$badgeColor['g'].", ".$badgeColor['b'].", ".$badgeColor['a'].") !important; border-left-color: transparent !important; }";
            }

            if ( $badgeStyle == 'bsone' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsone acoplwLeftAlign" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsone acoplwRightAlign" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }
            if ( $badgeStyle == 'bstwo' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bstwo acoplwLeftAlign '.$customClass.'" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bstwo acoplwRightAlign '.$customClass.'" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }
            if ( $badgeStyle == 'bsthree' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsthree acoplwLeftAlign '.$customClass.'" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsthree acoplwRightAlign '.$customClass.'" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }
            if ( $badgeStyle == 'bsfour' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsfour acoplwLeftAlign '.$customClass.'" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsfour acoplwRightAlign '.$customClass.'" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }
            if ( $badgeStyle == 'bsfive' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsfive acoplwLeftAlign" style="'.$css.'"><span class="acoplw-blockOne" style="'.$blockonecss.'"></span><span class="acoplw-blockTwo"></span><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsfive acoplwRightAlign" style="'.$css.'"><span class="acoplw-blockOne" style="'.$blockonecss.'"></span><span class="acoplw-blockTwo"></span><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }
            if ( $badgeStyle == 'bssix' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bssix acoplwLeftAlign" style="'.$css.'"><span class="acoplw-blockOne" style="'.$blockonecss.'"></span><span class="acoplw-blockTwo"></span><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bssix acoplwRightAlign" style="'.$css.'"><span class="acoplw-blockOne" style="'.$blockonecss.'"></span><span class="acoplw-blockTwo"></span><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }
            if ( $badgeStyle == 'bsseven' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsseven acoplwLeftAlign" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bsseven acoplwRightAlign" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }
            if ( $badgeStyle == 'bseight' ) {
                if ( $badgePositionHorizontal == 'bpthree' ) {
                    $badge = '<span class="acoplw-badge-icon acoplw-bseight acoplwLeftAlign" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                } else {
                    $badge = '<span class="acoplw-badge-icon acoplw-bseight acoplwRightAlign" style="'.$css.'"><span class="acoplw-blockText" style="'.$textcss.'">'.$label.'</span></span>';
                }
            }

            $this->acoplwBadges[$productID][$badgeID] = $badge;
            $this->customStyles[$badgeID] = $customStyle;

        }

    }

    // Load Active Badges
    public function load_badges()
    {

        if ( $this->active_badges === false ) {

            // Get wordpress timezone settings
            $gmt_offset = get_option('gmt_offset');
            $timezone_string = get_option('timezone_string');
            if ($timezone_string) {
                $datenow = new DateTime(current_time('mysql'), new DateTimeZone($timezone_string));
            } else {
                $min = 60 * get_option('gmt_offset');
                $sign = $min < 0 ? "-" : "+";
                $absmin = abs($min);
                $tz = sprintf("%s%02d%02d", $sign, $absmin / 60, $absmin % 60);
                $datenow = new DateTime(current_time('mysql'), new DateTimeZone($tz));
            }

            // Converting to UTC+000 (moment isoString timezone)
            $datenow->setTimezone(new DateTimeZone('+000'));
            $datenow = $datenow->format('Y-m-d H:i:s');
            $stop_date = date('Y-m-d H:i:s', strtotime($datenow . ' +1 day'));

            $day = date("l");
            $acoplw_badge_args = array(
                'post_type' => ACOPLW_POST_TYPE,
                'fields' => 'ids',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'badge_status',
                        'value' => 1,
                        'compare' => '=',
                        'type' => 'NUMERIC'
                    ),
                    array(
                        'key' => 'badge_start_date',
                        'value' => $datenow,
                        'compare' => '<=',
                        'type' => 'DATETIME'
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => 'badge_end_date',
                            'value' => $datenow,
                            'compare' => '>=',
                            'type' => 'DATETIME'
                        ),
                        array(
                            'key' => 'badge_end_date',
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key' => 'badge_end_date',
                            'value' => '',
                            'compare' => '=',
                        ),
                    )
                )
            );

            $acoplw_badge_rules     = get_posts($acoplw_badge_args); 
            $acoplw_active_badges   = $check_rules = array();
            // Multi Lang
            $checkML                = call_user_func ( array ( new ACOPLW_ML(), 'is_default_lan' ), '' );
            $currentLang            = !$checkML ? call_user_func ( array ( new ACOPLW_ML(), 'current_language' ), '' ) : '';

            if ( $acoplw_badge_rules ) {

                foreach ( $acoplw_badge_rules as $acoplwID ) {

                    $schedules          = unserialize(get_post_meta($acoplwID, 'badge_schedules', true)); 
                    $pschedule          = get_post_meta($acoplwID, 'badge_use_pschedule', true);
                    
                    $label_options      = get_post_meta($acoplwID, 'badge_label_options', true);
                    $style_options      = get_post_meta($acoplwID, 'badge_style_options', true);
                    $position_options   = get_post_meta($acoplwID, 'badge_position_options', true);
                    $preview_options    = get_post_meta($acoplwID, 'badge_preview_options', true);

                    $onSaleProducts     = $preview_options['assignAll'];

                    if ( $pschedule && $onSaleProducts ) { // WC Sale Schedule

                        if ( !in_array( $acoplwID, $check_rules ) ) {

                            $this->pScheduleStatus[$acoplwID]   = true;
                            $check_rules[]                      = $acoplwID; // remove repeated entry - single rule

                            // Multi Lang
                            if ( $currentLang ) { 
                                $langLabel          = array_key_exists ( 'badgeLabelLang', $label_options ) ? $label_options['badgeLabelLang'] : [];
                                $MLBadgeLabel       = !empty ( $langLabel ) ? ( array_key_exists ( $currentLang, $langLabel ) ? $langLabel[$currentLang] : $label_options['badgeLabel'] ) : ( ( $label_options['badgeLabel'] != '' ) ? $label_options['badgeLabel'] : get_the_title ( $acoplwID ) );
                            } else {
                                $MLBadgeLabel       = $label_options['badgeLabel'];
                            } 

                            $acoplw_active_badges[$acoplwID] = array(

                                'id'                        => $acoplwID,

                                'label'                     => $MLBadgeLabel,
                                'labelColor'                => $label_options['badgeLabelColor'],
                                'fontSize'                  => $label_options['fontSize'],
                                'lineHeight'                => $label_options['lineHeight'],

                                'badgeStyle'                => $style_options['badgeStyle'],
                                'badgeColor'                => $style_options['badgeColor'],
                                'badgeWidth'                => $style_options['badgeWidth'],
                                'badgeHeight'               => $style_options['badgeHeight'],
                                'borderTopLeft'             => $style_options['borderTopLeft'],
                                'borderTopRight'            => $style_options['borderTopRight'],
                                'borderBottomLeft'          => $style_options['borderBottomLeft'],
                                'borderBottomRight'         => $style_options['borderBottomRight'],

                                'opacity'                   => $position_options['opacity'],
                                'rotationX'                 => $position_options['rotationX'],
                                'rotationY'                 => $position_options['rotationY'],
                                'rotationZ'                 => $position_options['rotationZ'],
                                'flipHorizontal'            => $position_options['flipHorizontal'],
                                'flipVertical'              => $position_options['flipVertical'],
                                'badgePosition'             => $position_options['badgePosition'],
                                'badgePositionHorizontal'   => $position_options['badgePositionHorizontal'],
                                'posTop'                    => $position_options['posTop'],
                                'posBottom'                 => $position_options['posBottom'],
                                'posLeft'                   => $position_options['posLeft'],
                                'posRight'                  => $position_options['posRight'],

                            );

                        }

                    } else {

                        $this->pScheduleStatus[$acoplwID] = false;

                        foreach ( $schedules as $schedule ) {

                            $mn_start_time      = date('H:i' , strtotime($schedule['start_date']));
                            $mn_end_time        = date('H:i' , strtotime($schedule['end_date']));
                            $current_time       = strtotime(gmdate('H:i'));
                            $acoplw_start_date  = $schedule['start_date'];
                            $acoplw_end_start   = $schedule['end_date'] ? $schedule['end_date'] : $stop_date;

                            if ( ( $acoplw_start_date <= $datenow ) && ( $acoplw_end_start >= $datenow ) && !in_array( $acoplwID, $check_rules ) ) {

                                $check_rules[] = $acoplwID; // remove repeated entry - single rule

                                // Multi Lang
                                if ( $currentLang ) { 
                                    $langLabel          = array_key_exists ( 'badgeLabelLang', $label_options ) ? $label_options['badgeLabelLang'] : [];
                                    $MLBadgeLabel       = !empty ( $langLabel ) ? ( array_key_exists ( $currentLang, $langLabel ) ? $langLabel[$currentLang] : $label_options['badgeLabel'] ) : ( ( $label_options['badgeLabel'] != '' ) ? $label_options['badgeLabel'] : get_the_title ( $acoplwID ) );
                                } else {
                                    $MLBadgeLabel       = $label_options['badgeLabel'];
                                } 

                                $acoplw_active_badges[$acoplwID] = array(

                                    'id'                        => $acoplwID,

                                    'label'                     => $MLBadgeLabel,
                                    'labelColor'                => $label_options['badgeLabelColor'],
                                    'fontSize'                  => $label_options['fontSize'],
                                    'lineHeight'                => $label_options['lineHeight'],

                                    'badgeStyle'                => $style_options['badgeStyle'],
                                    'badgeColor'                => $style_options['badgeColor'],
                                    'badgeWidth'                => $style_options['badgeWidth'],
                                    'badgeHeight'               => $style_options['badgeHeight'],
                                    'borderTopLeft'             => $style_options['borderTopLeft'],
                                    'borderTopRight'            => $style_options['borderTopRight'],
                                    'borderBottomLeft'          => $style_options['borderBottomLeft'],
                                    'borderBottomRight'         => $style_options['borderBottomRight'],

                                    'opacity'                   => $position_options['opacity'],
                                    'rotationX'                 => $position_options['rotationX'],
                                    'rotationY'                 => $position_options['rotationY'],
                                    'rotationZ'                 => $position_options['rotationZ'],
                                    'flipHorizontal'            => $position_options['flipHorizontal'],
                                    'flipVertical'              => $position_options['flipVertical'],
                                    'badgePosition'             => $position_options['badgePosition'],
                                    'badgePositionHorizontal'   => $position_options['badgePositionHorizontal'],
                                    'posTop'                    => $position_options['posTop'],
                                    'posBottom'                 => $position_options['posBottom'],
                                    'posLeft'                   => $position_options['posLeft'],
                                    'posRight'                  => $position_options['posRight'],

                                );

                            }

                        }

                    }

                }

            }
            
            $this->active_badges = $acoplw_active_badges;

        }

    }

    public function check_in_product_list ( $productID, $badgeID )
    {

        $productListSelected = get_post_meta( $badgeID, 'badge_selected_list', true );
        $preview_options = get_post_meta( $badgeID, 'badge_preview_options', true );

        $productsOnSale = $preview_options['assignAll'];

        if ( $productsOnSale == true ) {

            return $this->check_product_on_sale( $productID );

        } else { 
            
            if ( '' == $productListSelected ) {

                return true;

            } else {

                $this->set_product_list();
                return isset($this->product_lists[$productListSelected]) &&
                    in_array($productID, $this->product_lists[$productListSelected]);

            }

        }

    }

    // Products On Sale
    public function check_product_on_sale( $productID )
    {

        if ( false == $this->products_on_sale ) {
            
            global $wpdb;
            
            $acoplw_onsale_prods = $wpdb->get_results( "
                SELECT posts.ID as id, posts.post_parent as parent_id
                FROM {$wpdb->posts} AS posts
                INNER JOIN {$wpdb->wc_product_meta_lookup} AS lookup ON posts.ID = lookup.product_id
                WHERE posts.post_type IN ( 'product', 'product_variation' )
                AND posts.post_status = 'publish'
                AND lookup.onsale = 1
                AND posts.post_parent NOT IN (
                    SELECT ID FROM `$wpdb->posts` as posts
                    WHERE posts.post_type = 'product'
                    AND posts.post_parent = 0
                    AND posts.post_status != 'publish'
                )
                GROUP BY posts.ID
                " 
            );

            $prods_onSale = wp_parse_id_list( array_merge( wp_list_pluck( $acoplw_onsale_prods, 'id' ), array_diff( wp_list_pluck( $acoplw_onsale_prods, 'parent_id' ), array( 0 ) ) ) );
            
            $this->products_on_sale = $prods_onSale;

        }

        $onSaleIDs = $this->products_on_sale; 

        return in_array ( $productID, $onSaleIDs ) ? true : false;

    }

    // Product List
    public function set_product_list()
    {

        if ( false == $this->product_lists ) {

            $checkML                = call_user_func ( array ( new ACOPLW_ML(), 'is_default_lan' ), '' );
            $currentLang            = !$checkML ? call_user_func ( array ( new ACOPLW_ML(), 'current_language' ), '' ) : 'default';

            if ( false === ( $product_lists = get_transient(ACOPLW_PRODUCTS_TRANSIENT_KEY) ) || get_transient(ACOPLW_PRODUCTS_TRANSIENT_KEY) != $currentLang ) {

                $post_type = ACOPLW_PRODUCT_LIST;
                global $wpdb;

                $product_lists = array();

                $lists = array_values ( array_diff ( array_filter ( $wpdb->get_col ( $wpdb->prepare ( 
                        "SELECT pm.meta_value FROM {$wpdb->postmeta} pm
                        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                        WHERE pm.meta_key = '%s' 
                        AND p.post_status = '%s' 
                        AND p.post_type = '%s'", 'badge_selected_list', 'publish', ACOPLW_POST_TYPE ) ) ), array ( "null" ) ) ); 


                $post_ids = array_map ( function($value) { return (int)$value; }, $lists ); 

                foreach ($post_ids as $id) {

                    $list_type = get_post_meta($id, 'list_type', true); 
                    $other_config = get_post_meta($id, 'product_list_config', true);

                    $product_lists[$id] = array(); 

                    if ('products_selection' == $list_type) {

                        $product_lists[$id] = ($other_config['selectedProducts']);

                    } else { 

                        $tax_rules = ($other_config['rules']);
                        $tax_rules = ($tax_rules && is_array($tax_rules) && !empty($tax_rules)) ? $tax_rules : false;
                        $excludedProducts = ($other_config['excludedProducts']);
                        $args = array(
                            'post_type' => ACOPLW_PRODUCTS,
                            'posts_per_page' => -1,
                            'post__not_in' => $excludedProducts
                        ); 

                        if (false !== $tax_rules) {

                            if (isset($tax_rules[0]['rules']) && is_array($tax_rules[0]['rules'])) {

                                $tax_query = array(
                                    'relation' => ('or' == strtolower($other_config['taxRelation'])) ? 'OR' : 'AND'
                                );

                                foreach ( array_filter ( $tax_rules[0]['rules'] ) as $tr ) { 
                                    $taxoperator = ( $tr['rule']['condition'] === 'notin' ) ? 'NOT IN' : 'IN'; 
                                    $tax_query[] = array(
                                        'taxonomy'  => $tr['rule']['item'],
                                        'field'     => 'term_id',
                                        'terms'     => $tr['rule']['value'],
                                        'operator'  => $taxoperator
                                    );
                                }
                                $args['tax_query'] = $tax_query;

                            }

                        }

                        $query = new WP_Query($args);
                        $product_lists[$id] = wp_list_pluck($query->posts, 'ID');

                    }

                    if ( $product_lists[$id] && class_exists('SitePress') ) { // Get WPML Product ids @@ 1.1.4
                        $wpmlPosts = [];
                        foreach ( $product_lists[$id] as $product_list_id ) { 
                            $transID = apply_filters( 'wpml_object_id', $product_list_id, 'product' );
                            if ( $transID ) {
                                $wpmlPosts[] = $transID;
                            }
                        }
                        $product_lists[$id] = array_values ( array_unique ( array_merge ( $product_lists[$id], $wpmlPosts ) ) );
                    }

                }

                set_transient(ACOPLW_PRODUCTS_TRANSIENT_KEY, $product_lists, 7 * 24 * HOUR_IN_SECONDS);
                set_transient(ACOPLW_PRODUCTS_LANG_TRANSIENT_KEY, $currentLang, 7 * 24 * HOUR_IN_SECONDS);

            }

            $this->product_lists = $product_lists; 
        }

    }

    public function onSaleScheduleList( $productID, $badgeID ) {

        $listitems = $this->products_on_sale;

        if ( false == $this->product_schedule_onsale ) {

            // if ( false === ( $acoplw_products_onsale = get_transient ( ACOPLW_PRODUCTS_SCHEDULE_TRANSIENT_KEY ) ) ) {

                $timezone_string = get_option('timezone_string');
                if ($timezone_string) {
                    $datenow = new DateTime(current_time('mysql'), new DateTimeZone($timezone_string));
                } else {
                    $min = 60 * get_option('gmt_offset');
                    $sign = $min < 0 ? "-" : "+";
                    $absmin = abs($min);
                    $tz = sprintf("%s%02d%02d", $sign, $absmin / 60, $absmin % 60);
                    $datenow = new DateTime(current_time('mysql'), new DateTimeZone($tz));
                }
                // Converting to UTC+000 (moment isoString timezone)
                $datenow->setTimezone(new DateTimeZone('+000'));
                $datenow = strtotime($datenow->format('Y-m-d'));

                $acoplw_sale_args = array(
                    'post_type' => ACOPLW_PRODUCTS,
                    'fields' => 'ids',
                    'post_status' => 'publish',
                    'include' => $listitems,
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'relation' => 'AND',
                            array(
                                'key' => '_sale_price_dates_from',
                                'value' => $datenow,
                                'compare' => '<=',
                                'type' => 'NUMERIC'
                            ),
                            array(
                                'key' => '_sale_price_dates_to',
                                'value' => $datenow,
                                'compare' => '>=',
                                'type' => 'NUMERIC'
                            )
                        ),
                        array(
                            'relation' => 'AND',
                            array(
                                'key' => '_sale_price_dates_from',
                                'value' => $datenow,
                                'compare' => '<=',
                                'type' => 'NUMERIC'
                            ),
                            array(
                                'key' => '_sale_price_dates_to',
                                'value' => '',
                                'compare' => '=',
                            ),
                        )
                    )
                );

                $acoplw_products_onsale = get_posts($acoplw_sale_args); 

                // set_transient ( ACOPLW_PRODUCTS_SCHEDULE_TRANSIENT_KEY, $acoplw_products_onsale, 7 * 24 * HOUR_IN_SECONDS );

            // } // Loop End

            $this->product_schedule_onsale = $acoplw_products_onsale;

            if ( in_array ( $productID, $this->product_schedule_onsale ) ) {

                return true;

            } else {

                return false;

            }
        
        }

    }

    public function customStyles()
    {

        $styles = $this->customStyles;
        $wc_badge = get_option('acoplw_wc_badge_status');
        
        if ( $styles ) {

            $result = '<style>.products .acoplw-badge-icon{visibility:visible;} ';
            $result .= $wc_badge ? '.onsale{display:none !important;} ' : '';
            foreach ( $styles as $style ) {
                $result = $result.$style;
            }
            $result .= '</style>';
            echo $result;

        } else if ( $wc_badge ) {

            $result = '<style>.products .acoplw-badge-icon{visibility:visible;} ';
            $result .= $wc_badge ? '.onsale{display:none !important;} ' : '';
            $result .= '</style>';
            echo $result;

        }

    }

    /** 
     * Cloning is forbidden.
     * @since 1.0.0
    **/
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

    /** 
     * Unserializing instances of this class is forbidden.
     * @since 1.0.0
    **/
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

}
