<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style>
@page {
    margin: 0cm 0cm;
}

body {
  margin: 18pt 18pt 24pt 18pt;
  /* Default TEXT */
  font-size: 12px;
}

* {
  font-family: helvetica;
}

p {
    font-size: 12px;
    /* font-weight: bold; */
}

p.title {
    font-size: 12px;
    font-weight: bold;
}

.pt {
    font-size: 10px;
    text-align: right;
    padding-right: 75px;
}

.no-border {
    border-spacing: 0px;
}

td, th {
  font-size: 12px;
}

.heading table {
    width: 100%;
}

.heading .htd {
    width: 50%;
}

.body table {
    width: 100%;
}

.body .tdb {
    width: 50%;
}

.footer {
    width: 100%;
}

.footer .ttd {
    text-align: right;
    vertical-align: top;
    padding-right: 50px;
    height:100px;
}

.content table {
    width: 100%;
    border: 1px solid black;
}

.content thead tbody {
    width: 100%;
}

table.tb {
    margin-top: 20px;
    margin-bottom: 20px;
}

.tb tr:first-child th {
    border: solid black 1px;;
}

.tb tr:last-child td {
    border: solid black 1px;;
}

.thc {
    border-right: 1px solid black;
    border-bottom: 1px solid black;
    text-align: center;
    /* width: 10%; */
}

.thr {
    border-bottom: 1px solid black;
    text-align: center;
    /* width: 25%; */
}

.tdl{
    border-right: 1px solid black;
    border-left: 1px solid black;
    padding-left: 5px;
    text-align: center;
}

.tdr{
    text-align: left;
    padding-left: 5px;
    border-right: solid black 1px;
}

.tdc{
    width: 50%;
    text-align: left;
    border-right: 1px solid black;
    padding-left: 5px;
}

.tdg {
    text-align: right;
    padding-right: 5px;
    border-top: 1px solid black;
    border-right: 1px solid black;
    border-left: 1px solid black;
}

.tdgt {
    text-align: right;
    padding-right: 5px;
}

.padding-table {
  padding: 5px;
}

.header {
    text-align: center;
}

.header h1,
.header h2,
.header h3,
.header h4,
.header h5,
.header h6 {
    margin: 0px;
}

hr.header {
    border: 2px solid black;
}

h3 {
    margin: 0px;
}

/* ==== Table Detail ==== */
table.table-detail {
    margin-top: 10px;
    margin-bottom: 20px;
}

table.table-detail td {
    border: 1px solid black;
}

table.table-detail thead td {
    font-weight: bold;
}

table.table-detail tfoot td {
    font-weight: normal;
}

table.table-detail tbody td {
    border-top: 0px solid black;
    border-bottom: 0px solid black;
    border-left: 1px solid black;
    border-right: 1px solid black;
}

/* ==== Table Footer ==== */
table.table-footer {
    width: 100%;
}

table.table-footer td.col-sign {
    width: 30%;
    height: 100px;
    border-bottom: 1px solid black
}

.col-id {
    width: 5%;
}

.col-qty {
    width: 10%;
}

.col-unit {
    width: 10%;
}

.col-price {
    width: 15%;
}

.text-left {
    text-align: left;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.text-top {
    vertical-align: top;
}

.text-bottom {
    vertical-align: bottom;
}

.ref-no {
    font-size: 10px;
}

.price {
    font-size: 20px;
}

.info-container {
    margin-top: 50px;
}

.clear {
    clear: both;
}

.info {
    margin-top: 50px;
}

p {
    margin: 0px;
    font-size: 12px;
    font-weight: normal;
}

.table-detail tbody {
    border: 1px solid black;
}

.table-detail tbody td, .table-detail thead td, .table-detail tfoot td {
    border-bottom: 1px solid black !important;
    padding: 3px 5px;
    box-sizing: border-box;
}

table thead tr td {
  background: #e0ffff;
  padding: 10px 0;
}
/**
* Define the width, height, margins and position of the watermark.
**/
#watermark {
    position: fixed;
    bottom:   0px;
    left:     0px;
    /** The width and height may change
        according to the dimensions of your letterhead
    **/
    width:    21.8cm;
    height:   28cm;

    /** Your watermark should be behind every content**/
    z-index:  -1000;
}

#footer {
  width: 100%;
  bottom: 100px;
}

.logo {
  height: auto;
}

.logo img {
  object-fit: cover;
}

header {
  /*
  position: fixed;
  top: 20px;
  left: 70px;
  right: 0px;
  */
}

header p {
    font-size: 10px;
}

footer {
  position: fixed;
  left: 75px;
  right: 0px;
  bottom: -60px;
}

.border-outline {
    border: 1px solid #000;
    padding: 2px 2px;
    margin: 4px 0 8px 0;
}

.wd50 {
    width: 50%;
}

* {
    font-family: monospace;
    line-height: 1.4 !important;
}

.sserif {
    font-family: sans-serif;
}

.ft14 {
    font-size: 12px;
}

/* Table gema template */
table.gema-header td{
    border: 1px solid #000;
    padding: 5px;
}

table.gema {
    margin-top: 2px;
}

table.gema td,
table.gema th
{
    padding: 2px;
}

table.gema thead,
table.gema tfoot
{
    border-top: 1px solid #000 !important;
    border-bottom: 1px solid #000 !important;
}

table.gema tr.no-border td{
    border: 0px;
}

.border-top {
    border-top: 1px solid #000 !important;
}

.border-bottom {
    border-bottom: 1px solid #000 !important;
}

.with-padding-top {
    padding-top: 100px;
}

table.address td {
    border: 0px;
    padding: 0px;
}

.vertical {
    border-left: 1px solid black;
    height: 100%;
    position:absolute;
    left: 50%;
}

</style>
</head>
<body>
<div id="watermark">
  <!-- <img src="{{URL::asset('/img/background.png')}}" height="100%" width="100%" alt=""> -->
</div>

<header class="logo">
    <img src="" height="64" width="auto" alt="LOGO SAA">
    <p>Head Office : </p>
    <p>Branch Office : </p>
    <p>Telp & Fax : </p>
    <p>Email: <a href="#"> email*** </a></p>
</header>

<h1>@yield('title')</h1>

@section('content')
    This is the master content.
@show
</body>
</html>
