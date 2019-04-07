<?php

namespace aegis\dolos\Wrappers;

class SecurityWrapper extends Wrapper
{

    private $active = 0;


    private $reg_SQL = "/(drop|insert|md5|select|union)/i";

    private $reg_JS = "/(alert\(|applet\(|vbs\(|eval\(|cookie\(|prompt\(|confirm\(|cmd\(|passthru\(|exec\(|expression\(|system\(|fopen\(|fsockopen\(|file\(|file_get_contents\(|readfile\(|unlink\(|write\()/i";
    private $eventHandlers = "/(FSCommand\(|onAbort\(|onActivate\(|onAfterPrint\(|onAfterUpdate\(|onBeforeActivate\(|onBeforeCopy\(|onBeforeCut\(|onBeforeDeactivate\(|onBeforeEditFocus\(|onBeforePaste\(|onBeforePrint\(|onBeforeUnload\(|onBeforeUpdate\(|onBegin\(|onBlur\(|onBounce\(|onCellChange\(|onChange\(|onClick\(|onContextMenu\(|onControlSelect\(|onCopy\(|onCut\(|onDataAvailable\(|onDataSetChanged\(|onDataSetComplete\(|onDblClick\(|onDeactivate\(|onDrag\(|onDragEnd\(|onDragLeave\(|onDragEnter\(|onDragOver\(|onDragDrop\(|onDragStart\(|onDrop\(|onEnd\(|onError\(|onErrorUpdate\(|onFilterChange\(|onFinish\(|onFocus\(|onFocusIn\(|onFocusOut\(|onHashChange\(|onHelp\(|onInput\(|onKeyDown\(|onKeyPress\(|onKeyUp\(|onLayoutComplete\(|onLoad\(|onLoseCapture\(|onMediaComplete\(|onMediaError\(|onMessage\(|onMouseDown\(|onMouseEnter\(|onMouseLeave\(|onMouseMove\(|onMouseOut\(|onMouseOver\(|onMouseUp\(|onMouseWheel\(|onMove\(|onMoveEnd\(|onMoveStart\(|onOffline\(|onOnline\(|onOutOfSync\(|onPaste\(|onPause\(|onPopState\(|onProgress\(|onPropertyChange\(|onReadyStateChange\(|onRedo\(|onRepeat\(|onReset\(|onResize\(|onResizeEnd\(|onResizeStart\(|onResume\(|onReverse\(|onRowsEnter\(|onRowExit\(|onRowDelete\(|onRowInserted\(|onScroll\(|onSeek\(|onSelect\(|onSelectionChange\(|onSelectStart\(|onStart\(|onStop\(|onStorage\(|onSyncRestored\(|onSubmit\(|onTimeError\(|onTrackChange\(|onUndo\(|onUnload\(|onURLFlip\(|seekSegmentTime\()/i";

    private $htmlPattern = '#%s=.*(?:javascript:|view-source:|livescript:|wscript:|vbscript:|mocha:|charset=|window\.|\(?document\)?\.|\.cookie|<script|d\s*a\s*t\s*a\s*:)#is';

    private $patternMatch = ['href', 'src'];


    private $nonAllowed = [
        // default javascript
        'javascript\s*:',
        // default javascript
        '(\(?document\)?|\(?window\)?(\.document)?)\.(location|on\w*)',
        // Java: jar-protocol is an XSS hazard
        'jar\s*:',
        // Mac (will not run the script, but open it in AppleScript Editor)
        'applescript\s*:',
        // IE: https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet#VBscript_in_an_image
        'vbscript\s*:',
        // IE, surprise!
        'wscript\s*:',
        // IE
        'jscript\s*:',
        // IE: https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet#VBscript_in_an_image
        'vbs\s*:',
        // https://html5sec.org/#behavior
        'behavior\s:',
        // ?
        'Redirect\s+30\d',
        // data-attribute + base64
        "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?",
        // remove Netscape 4 JS entities
        '&\s*\{[^}]*(\}\s*;?|$)',
        // old IE, old Netscape
        'expression\s*(\(|&\#40;)',
        // old Netscape
        'mocha\s*:',
        // old Netscape
        'livescript\s*:',
        // default view source
        'view-source\s*:',
    ];

    public function process()
    {
        $this->initWrapper($this->setLocalName());

        $this->sqlDetection();
        $this->jsDetection();
        $this->eventHandlerDetection();

        $this->nonAllowedDetection();

        if ($this->active == 1) {
            $this->setScore($this->getRealScore());
            $this->setResult();
        }
    }


    private function setLocalName()
    {
        $name = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        return str_replace('Wrapper', '', $name);
    }

    private function sqlDetection()
    {
        if (preg_match($this->reg_SQL, $this->getReference())) {
            $this->setName('sql');
            $this->active = 1;
        }
    }

    private function jsDetection()
    {
        $type = 'javascript';
        if (preg_match($this->reg_JS, $this->getReference())) {
            $this->setName($type);
            $this->active = 1;
        }

        foreach ($this->patternMatch as $match) {

            $pattern = sprintf($this->htmlPattern, $match);

            if (preg_match($pattern, $this->getReference())) {
                $this->setName($type);
                $this->active = 1;
            }
        }

    }
    private function eventHandlerDetection()
    {
        $type = 'eventHandler';
        if (preg_match($this->eventHandlers, $this->getReference())) {
            $this->setName($type);
            $this->active = 1;
        }
    }

    private function nonAllowedDetection()
    {
        foreach ($this->nonAllowed as $value) {

            if (preg_match('#' . $value . '#is', $this->getReference())) {
                $this->setName('nonAllowed');
                $this->active = 1;
            }
        }
    }

}