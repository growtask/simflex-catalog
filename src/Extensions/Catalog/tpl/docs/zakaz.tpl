<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Заказ № <?= $o->order_id ?></title>
    <meta name="ProgId" content="Excel.Sheet">
    <meta name="Generator" content="Aspose.Cells 23.9">
    <style>
        tr {
            mso-height-source: auto;
            mso-ruby-visibility: none;
        }

        col {
            mso-width-source: auto;
            mso-ruby-visibility: none;
        }

        br {
            mso-data-placement: same-cell;
        }

        ruby {
            ruby-align: left;
        }

        .style0 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
            mso-style-name: Normal;
            mso-style-id: 0;
        }

        .font15 {
            color: #000000;
            font-size: 22pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
        }

        .font17 {
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
        }

        .font18 {
            color: #000000;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
        }

        .font19 {
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: '"Times New Roman"', sans-serif;
        }

        .font21 {
            color: #000000;
            font-size: 13pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
        }

        .font22 {
            color: #000000;
            font-size: 11pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
        }

        .font23 {
            color: #FF0000;
            font-size: 22pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
        }

        .font24 {
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            text-decoration: underline;
            font-family: 'Times New Roman', sans-serif;
        }

        .font25 {
            color: #000000;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            text-decoration: line-through;
            font-family: 'Times New Roman', sans-serif;
        }

        .font26 {
            color: #FF0000;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
        }

        .font27 {
            color: #FF0000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: '"Times New Roman"', sans-serif;
        }

        td {
            mso-style-parent: style0;
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
            mso-ignore: padding;
        }

        .x15 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x21 {
            mso-number-format: General;
            text-align: general;
            vertical-align: middle;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x22 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x23 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x24 {
            mso-number-format: General;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x25 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: none;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x26 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x27 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x28 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x29 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x30 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x31 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x32 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x33 {
            mso-number-format: General;
            text-align: general;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x34 {
            mso-number-format: General;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x35 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x36 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 700;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x37 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x38 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x39 {
            mso-number-format: General;
            text-align: general;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x40 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x41 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x42 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x43 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x44 {
            mso-number-format: General;
            text-align: general;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x45 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x46 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x47 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x48 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x49 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x50 {
            mso-number-format: General;
            text-align: general;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x51 {
            mso-number-format: General;
            text-align: general;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x52 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x53 {
            mso-number-format: General;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x54 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x55 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 18pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x56 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x57 {
            mso-number-format: General;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x58 {
            mso-number-format: General;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x59 {
            mso-number-format: General;
            text-align: general;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x60 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x61 {
            mso-number-format: General;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x62 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x63 {
            mso-number-format: General;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x64 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 18pt;
            font-weight: 700;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x65 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 11pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x66 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 11pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x67 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 9pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x68 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 9pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x69 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 9pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x70 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x71 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x72 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x73 {
            mso-number-format: General;
            text-align: general;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 700;
            font-style: normal;
            font-family: "Arial", sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x74 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 700;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x75 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 13pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x76 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x77 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 13pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x78 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x79 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 13pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x80 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 13pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x81 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 24pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x82 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x83 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x84 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x85 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x86 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x87 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: none;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x88 {
            mso-number-format: General;
            text-align: left;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: none;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x89 {
            mso-number-format: General;
            text-align: right;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x90 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            border-left: 1px solid #000000;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x91 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x92 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x93 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x94 {
            mso-number-format: General;
            text-align: right;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x95 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 12pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 1px solid #000000;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x96 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 22pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 2px solid #CCCCCC;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x97 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: 2px solid #CCCCCC;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x98 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x99 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x100 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x101 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: Arial, sans-serif;
            mso-protection: locked visible;
        }

        .x102 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: normal;
            word-wrap: break-word;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x103 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x104 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 10pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x105 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: '"Times New Roman"', sans-serif;
            mso-protection: locked visible;
        }

        .x106 {
            mso-number-format: General;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            mso-protection: locked visible;
        }

        .x107 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 2px solid #CCCCCC;
            border-right: 2px solid #CCCCCC;
            border-bottom: 2px solid #CCCCCC;
            border-left: 2px solid #CCCCCC;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x108 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: 2px solid #CCCCCC;
            border-right: 2px solid #CCCCCC;
            border-bottom: 2px solid #CCCCCC;
            border-left: 2px solid #CCCCCC;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x109 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #0000FF;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            text-decoration: underline;
            font-family: 'Times New Roman', sans-serif;
            border-top: 2px solid #CCCCCC;
            border-right: 2px solid #CCCCCC;
            border-bottom: 2px solid #CCCCCC;
            border-left: 2px solid #CCCCCC;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        .x110 {
            mso-number-format: General;
            text-align: general;
            vertical-align: bottom;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 700;
            font-style: normal;
            font-family: '"Times New Roman"', sans-serif;
            mso-protection: locked visible;
        }

        .x111 {
            mso-number-format: General;
            text-align: center;
            vertical-align: top;
            white-space: nowrap;
            background: auto;
            mso-pattern: auto;
            color: #000000;
            font-size: 14pt;
            font-weight: 400;
            font-style: normal;
            font-family: 'Times New Roman', sans-serif;
            border-top: none;
            border-right: none;
            border-bottom: none;
            border-left: none;
            mso-diagonal-down: none;
            mso-diagonal-up: none;
            mso-protection: locked visible;
        }

        #section {
            overflow: none;
            height: expression(window.screen.height - 39);
            width: window.screen.width;
            float: left;
            padding: 10px;
        }

        .push {
            width: 1px;
            height: 31px;
            clear: both;
        }

        #footer {
            background-color: #808080;
            width: 100%;
            text-align: center;
            padding: 5px;
            position: fixed;
            bottom: 0px;
        }
    </style>

</head>

<body link="blue" vlink="purple" bgcolor="white" topmargin="0" leftmargin="0">



<?php $lst = $o->getProducts('', '', 'COALESCE(is_deleted, 0) = 0');
if ($sub) {
    $o->sum_actual = 0;
    $o->sum_total = 0;

    $lst = [];
    foreach (\Simflex\Core\DB::assoc('select * from catalog_order_product_sub where order_id = ' . $id . ' and sub_num = ' . $sub) as $s) {
        $p = new \App\Extensions\Catalog\Model\Product($s['product_id']);
        $lst[] = [
            'product' => $p,
            'size' => $s['size'],
            'qty' => $s['qty'],
        ];

        $o->sum_actual += $s['sum'];
        $o->sum_total += $pi['price_old'] * $s['qty'];
    }
}
?>

    <table border="0" cellpadding="0" cellspacing="0" width="919" style="border-collapse: 
     collapse;table-layout:fixed;width: 100%">
        <colgroup>
            <col width="27" style="mso-width-source:userset;width:20.25pt">
            <col width="143" style="mso-width-source:userset;width:107.25pt">
            <col width="102" style="mso-width-source:userset;width:76.5pt">
            <col width="88" style="width:66pt">
            <col width="119" style="mso-width-source:userset;width:89.25pt">
            <col width="88" style="width:66pt">
            <col width="88" style="width:66pt">
            <col width="88" style="width:66pt">
            <col width="88" style="width:66pt">
            <col width="88" style="width:0pt">
        </colgroup>
        <tbody>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="19" class="x96" width="919"
                    style="border-bottom:2px solid #CCCCCC;height:14.25pt;">
                    <font class="font15" style="text-decoration: none;">Заказ №&nbsp;</font>
                    <font class="font23" style="text-decoration: none;"><?=$o->order_id?></font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="21" class="x99" style="mso-ignore:colspan;height:15.75pt;"></td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="19" class="x96" style="border-bottom:2px solid #CCCCCC;height:14.25pt;">
                    <font class="font15" style="text-decoration: none;">Сумма заказа составляет&nbsp;</font>
                    <font class="font23" style="text-decoration: none;"><?=$o->getTotal()?></font>
                    <font class="font15" style="text-decoration: none;">&nbsp;руб.</font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="21" class="x99" style="mso-ignore:colspan;height:15.75pt;"></td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" height="21" class="x100" style="height:15.75pt;">ДАННЫЕ ПОКУПАТЕЛЯ</td>
                <td colspan="5" class="x100">ДАННЫЕ ПОЛУЧАТЕЛЯ</td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="21" class="x101" style="mso-ignore:colspan;height:15.75pt;"></td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" height="21" class="x102" style="height:15.75pt;">
                    <font class="font17" style="text-decoration: none;">ФИО / Название организации (для юр
                        лиц):&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;">
                        <?php if ($o->org_active): ?>
                            <?=$o->org_name?>, ИНН <?=$o->org_inn?>
                        <?php else: ?>
                        <?=$o->user_last_name?><br><?=$o->user_name?> <?=$o->user_patronym?>
                        <?php endif; ?>
                    </font>
                </td>
                <td colspan="5" class="x102">
                    <font class="font17" style="text-decoration: none;">Транспортная компания:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->transcomp?></font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" height="21" class="x102" style="height:15.75pt;">
                    <font class="font17" style="text-decoration: none;">Email:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->email?></font>
                </td>
                <td colspan="5" class="x102">
                    <font class="font17" style="text-decoration: none;">ФИО получателя:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->last_name?> <?=$o->name?><br><?=$o->patronym?>
                    </font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" height="21" class="x102" style="height:15.75pt;">
                    <font class="font17" style="text-decoration: none;">Телефон:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->user_phone?></font>
                </td>
                <td colspan="5" class="x102">
                    <font class="font17" style="text-decoration: none;"></font>
                    <font class="font24" style="text-decoration:  underline;"></font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" rowspan="2" height="42" class="x102" style="height:31.5pt;">
                    <font class="font17" style="text-decoration: none;">ИНН, ОГРН (для
                        юр.лиц):&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;">
                        <?php if ($o->user->org_active): ?>
                        <?=$o->user->org_inn?>
                        <?php else: ?>

                        <?php endif; ?>
                    </font>
                </td>
                <td colspan="5" class="x102">
                    <font class="font17" style="text-decoration: none;">Телефон:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->user->phone?></font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" class="x102">
                    <font class="font17" style="text-decoration: none;">Город получения товара для ТК:&nbsp;
                    </font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->city?></font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" height="21" class="x102" style="height:15.75pt;">
                    <font class="font17" style="text-decoration: none;">Город:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->user->city?></font>
                </td>
                <td colspan="5" class="x102">
                    <font class="font17" style="text-decoration: none;">Адрес только для Почты России, ЕМС,
                        г.<br>Новосибирск:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->address?></font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" height="21" class="x102" style="height:15.75pt;">
                    <font class="font17" style="text-decoration: none;">&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"></font>
                </td>
                <td colspan="5" class="x102">
                    <font class="font17" style="text-decoration: none;">Комментарий:&nbsp;</font>
                    <font class="font24" style="text-decoration:  underline;"><?=$o->comment?></font>
                </td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="21" style="mso-ignore:colspan;height:15.75pt;"></td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="19" class="x96" style="border-bottom:2px solid #CCCCCC;height:14.25pt;">
                    Список товаров в заказе</td>
            </tr>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="10" height="21" class="x104" style="mso-ignore:colspan;height:15.75pt;"></td>
            </tr>
            <tr height="34" style="mso-height-source:userset;height:25.5pt">
                <td height="34" class="x105" style="height:25.5pt;">№</td>
                <td class="x106">Изображение</td>
                <td class="x106">Название</td>
                <td class="x106">Размер</td>
                <td class="x106">Количество</td>
                <td class="x106">Цена</td>
                <td class="x106">Сумма</td>
                <td class="x106">Наличие</td>
                <td class="x106">Замена</td>
                <td class="x98"></td>
            </tr>
            <?php $i=1;foreach ($lst as $pi): ?>
            <?php $p = $pi['product']; ?>
            <tr height="114" style="mso-height-source:userset;height:85.5pt">
                <td height="110" class="x107" style="height:82.5pt;"><?=$i++?></td>
                <td height="114" class="x107"  width="143" style="text-align: left;height:85.5pt;width:107.25pt;vertical-align:top;"
                    align="left">
                    
                    <span style="mso-ignore:vglayout2">
                        <table cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td height="114" class="x111" width="143" style="height:85.5pt;width:107.25pt;padding:5px">
                                        <span
                        style="mso-ignore:vglayout;z-index:1;margin-left:0px;margin-top:0px;width:163px;height:101px">

                            <?php
                            $ph = $p->getPreviewImage();
                            $ext = explode('.', $ph);
                            $ext = $ext[count($ext) - 1];
                            $data = base64_encode(file_get_contents(SF_ROOT_PATH . $ph));
                            ?>
                                            <img
                            width="100%" height="101"
                            src="data:image/<?=$ext?>;base64,<?=$data?>"
                            name="image1.jpg" alt="1">
                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </span>
                </td>
                <td class="x109"><a
                        href="<?=url($p->path)?>"
                        target="_parent"><span
                            style="font-size:14pt;color:#0000FF;font-weight:400;text-decoration: underline;text-line-through:none;text-underline-style:single;font-family:&quot;Times New Roman&quot;,sans-serif;"><?=str_replace(" ", '<br>', $p->name)?></span></a>
                </td>
                <td class="x107"><?=$pi['size']?></td>
                <td class="x107"><?=$pi['qty']?></td>
                <td class="x107" style="overflow:hidden;">
                    <?php if ($pi['price_old'] && $pi['price_old'] != $pi['price']): ?>
                    <font class="font25" style="text-decoration: line-through;"><?=number_format($pi['price_old'], 2, '.',' ')?></font>
                    <font class="font18" style="text-decoration: none;"><br></font>
                    <?php endif; ?>
                    <font class="font26" style="text-decoration: none;"><?=number_format($pi['price'], 2, '.',' ')?></font>
                    <font class="font18" style="text-decoration: none;">&nbsp;руб.</font>
                </td>
                <td class="x107" style="overflow:hidden;">
                    <?php if ($pi['price_old'] && $pi['price_old'] != $pi['price']): ?>
                    <font class="font25" style="text-decoration: line-through;"><?=number_format($pi['price_old'] * $pi['qty'], 0, '', ' ')?></font>
                    <font class="font18" style="text-decoration: none;"><br></font>
                    <?php endif; ?>
                    <font class="font26" style="text-decoration: none;"><?=number_format($pi['price'] * $pi['qty'], 0, '', ' ')?>&nbsp;</font>
                    <font class="font18" style="text-decoration: none;">руб.</font>
                </td>
                <td class="x107"><?php if ($p->stock): ?>
                    в<br>наличии
                <?php else: ?>
                        нет<br>наличии
                <?php endif; ?></td>
                <td class="x108"></td>
                <td class="x98"></td>
            </tr>
            <?php endforeach; ?>
            <tr height="21" style="mso-height-source:userset;height:15.75pt">
                <td colspan="5" height="21" class="x98" style="mso-ignore:colspan;height:15.75pt;"></td>
                <td colspan="2" class="x100">Сумма заказа:&nbsp;</td>
                <td colspan="2" class="x110">
                    <font class="font27" style="text-decoration: none;"><?=$o->getTotal()?></font>
                    <font class="font19" style="text-decoration: none;">&nbsp;руб</font>
                </td>
                <td class="x98"></td>
            </tr>
            <!--[if supportMisalignedColumns]-->
            <tr height="0" style="display:none">
                <td width="27" style="width:20.25pt;"></td>
                <td width="143" style="width:107.25pt;"></td>
                <td width="102" style="width:76.5pt;"></td>
                <td width="88" style="width:66pt;"></td>
                <td width="119" style="width:89.25pt;"></td>
                <td width="440" colspan="5" style="width:330pt;mso-ignore:colspan;"></td>
            </tr>
            <!--[endif]-->
        </tbody>
    </table>




    <?php if ($print): ?>
        <script>window.print();</script>
    <?php endif; ?>
</body>

</html>