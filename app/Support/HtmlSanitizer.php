<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Assainisseur HTML par liste blanche (sans dépendance externe).
 * Pour un besoin plus poussé : composer require mews/purifier.
 */
class HtmlSanitizer
{
    /** balise => attributs autorisés */
    private const ALLOWED = [
        'p' => ['class'], 'br' => [], 'hr' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'h2' => ['class'], 'h3' => ['class'], 'h4' => ['class'], 'h5' => [], 'h6' => [],
        'ul' => ['class'], 'ol' => ['class'], 'li' => [],
        'blockquote' => ['class'], 'pre' => [], 'code' => [],
        'span' => ['class'], 'div' => ['class'],
        'figure' => ['class'], 'figcaption' => [],
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'class'],
        'table' => ['class'], 'thead' => [], 'tbody' => [], 'tr' => [], 'th' => [], 'td' => [],
        'iframe' => ['src', 'width', 'height', 'title', 'allowfullscreen', 'frameborder'],
    ];

    /** balises supprimées avec leur contenu */
    private const DROP = [
        'script', 'style', 'object', 'embed', 'form', 'input', 'button', 'textarea',
        'select', 'option', 'link', 'meta', 'base', 'svg', 'math', 'template', 'noscript',
        'applet', 'frame', 'frameset',
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $previous = libxml_use_internal_errors(true);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div>' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementsByTagName('div')->item(0);
        if (! $root) {
            return e($html);
        }

        self::cleanChildren($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    private static function cleanChildren(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if (! $child instanceof DOMElement) {
                // texte conservé ; commentaires / instructions de traitement supprimés
                if (! in_array($child->nodeType, [XML_TEXT_NODE, XML_CDATA_SECTION_NODE], true)) {
                    $node->removeChild($child);
                }
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP, true)) {
                $node->removeChild($child);
                continue;
            }

            if ($tag === 'iframe' && ! self::isSafeEmbed($child->getAttribute('src'))) {
                $node->removeChild($child);
                continue;
            }

            self::cleanChildren($child);

            if (! isset(self::ALLOWED[$tag])) {
                // balise inconnue : on garde son contenu, on retire la balise
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            self::cleanAttributes($child, self::ALLOWED[$tag]);
        }
    }

    private static function cleanAttributes(DOMElement $el, array $allowed): void
    {
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);

            if (! in_array($name, $allowed, true) || str_starts_with($name, 'on')) {
                $el->removeAttribute($attr->name);
                continue;
            }

            if (in_array($name, ['href', 'src'], true) && $el->tagName !== 'iframe' && ! self::isSafeUrl($attr->value)) {
                $el->removeAttribute($attr->name);
            }
        }

        if (strtolower($el->tagName) === 'a') {
            $el->setAttribute('rel', 'noopener noreferrer nofollow');
        }
    }

    private static function isSafeUrl(string $url): bool
    {
        $url = preg_replace('/[\x00-\x20\x7f]+/', '', $url) ?? '';

        return (bool) preg_match('#^(https?://|mailto:|/(?!/)|\#)#i', $url);
    }

    private static function isSafeEmbed(string $src): bool
    {
        return (bool) preg_match(
            '#^https://(www\.youtube(-nocookie)?\.com/embed/|player\.vimeo\.com/video/)[A-Za-z0-9_\-?=&]+$#',
            $src
        );
    }
}