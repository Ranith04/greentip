<?php
//session_start();
/*******************************************************
 * Copyright (coffee) iTechFlock - All Rights Reserved
 * This file is part of "HelloTax" project.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 ********************************************************/
//require_once 'library.php';
class generateEfilingXml
{

    private $totEligibleDeduction = array();
    private $Eligible80G = array();
    private $salary_income;
    private $All_TotIncome;
    private $dob;
    public $userAge;
    public $gender;
    private $arrResponce = array();
    private $deductions = array();
    public $Curr_AssYear = '';
    private $reserveAmountFor80C;
    private $reserveAmountFor80CCD1B;
    private $reserveAmountFor80EE;
    private $itrInfoMain;
    private $GTI;

    public function __construct()
    {
    }

    public function getXml($jsonArr, $panNo, $Assesment_year, $Mobile, $flag)
    {

        $this->Curr_AssYear = $Assesment_year;
        $itrInfo = $jsonArr->ITR1;
        $this->itrInfoMain = $itrInfo;
        $totalTCSPaid = 0;
        foreach ($itrInfo->TaxPaid->Tcs as $key => $valueNonSal) {
            //print_r($valueNonSal);
            $totalTCSPaid = $totalTCSPaid + $valueNonSal->amount_claimed;
        }

        $Name = $itrInfo->bankinfo->name;

        $Name = explode(' ', $Name);
        $firstName = $Name[0];
        $middleName = $Name[1];
        $surName = $Name[2];

        if (trim($surName) == '') {
            $surName = $middleName;
            $middleName = '';
        }

        if (trim($surName) == '') {
            $surName = $firstName;
            $firstName = '';
        }

        $fullname = $firstName . ' ' . $middleName . ' ' . $surName;
        // }

        //comment yogendra
        //$jurisdiction = $userInfo->jurisdiction;
        $jurisdiction = "Noida";

        $totalBankAccounts = (count($itrInfo->bankinfo->bankDetail) + 1);
        $userAadhar = $itrInfo->UserInfo->userAadhar;
        $exempt_income = $itrInfo->exemptIncome->agricultureIncome;


        $totalexemptOtherIncome = (count($itrInfo->TaxPaid->exemptOtherIncome) + 1);


        $employerCategory = $itrInfo->UserInfo->EmployerCategory;
        //$itrInfo->UserInfo->EmailId;


        $dbhandler = new DbHandler();
        $emailId = $dbhandler->getEmailId($panNo);


        $fatherFullname = $itrInfo->bankinfo->fatherfullname;
        $pincode = $itrInfo->bankinfo->pincode;
        $statecode = $itrInfo->bankinfo->statecode;
        $cityortownordistrict = $itrInfo->bankinfo->cityortownordistrict;
        $LocalityOrArea = $itrInfo->bankinfo->localityorarea;
        //Address* (ResidenceNo,ResidenceName,RoadOrStreet)
        $address = $itrInfo->bankinfo->address;
        // $address ='asdsa,sadsf,sdfsd';
        $address = explode(',', $address);
        $ResidenceNo = $address[0];
        $ResidenceName = $address[1];
        $RoadOrStreet = $address[2];
        /**********************************************/

        $Userdeduction = $this->getUserDeduction($itrInfo);// deduction declare by USER

        // print_r($Userdeduction);
        /**********************************************/
        $total80G_detail = $this->getTotal80G_amount($itrInfo);
        $this->salary_income = $itrInfo->Income->salary_income;
        $this->dob = $itrInfo->UserInfo->UserDOB;
        $this->gender = $itrInfo->UserInfo->Gender;
        $this->userAge = $this->getUserAge($this->dob);
        $ay = explode('-', $Assesment_year);

        $sumTds = 0;
        $sumNonTdsSal = 0;
        $sumTdsAdvance = 0;
        foreach ($itrInfo->TaxPaid as $key => $valTaxPaid) {
            foreach ($valTaxPaid as $k => $taxAmount) {
                $sumTds += $taxAmount->total_tax_deduction;
                $sumNonTdsSal += $taxAmount->Amount_out_of_6_claimed_for_this_year;
                $sumTdsAdvance += $taxAmount->tax_paid;
            }
        }
        $totalTds = $sumTds + $sumNonTdsSal + $sumTdsAdvance;

        $strXml = '<?xml version="1.0" encoding="ISO-8859-1"?>
<ITRETURN:ITR xmlns:ITRETURN="http://incometaxindiaefiling.gov.in/main" xmlns:ITR1FORM="http://incometaxindiaefiling.gov.in/ITR1" xmlns:ITRForm="http://incometaxindiaefiling.gov.in/master" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<ITR1FORM:ITR1>
    <ITRForm:CreationInfo>
        <ITRForm:SWVersionNo>V1</ITRForm:SWVersionNo>
        <ITRForm:SWCreatedBy>Itechflock Software</ITRForm:SWCreatedBy>
        <ITRForm:XMLCreatedBy>ERIA101295</ITRForm:XMLCreatedBy>
        <ITRForm:XMLCreationDate>' . date('Y-m-d') . '</ITRForm:XMLCreationDate>
        <ITRForm:IntermediaryCity>Delhi</ITRForm:IntermediaryCity>
    </ITRForm:CreationInfo>
    <ITRForm:Form_ITR1>
        <ITRForm:FormName>ITR-1</ITRForm:FormName>
        <ITRForm:Description>For Indls having Income from Salary, Pension, family pension and Interest</ITRForm:Description>
        <ITRForm:AssessmentYear>' . $ay[0] . '</ITRForm:AssessmentYear>
        <ITRForm:SchemaVer>Ver1.0</ITRForm:SchemaVer>
        <ITRForm:FormVer>Ver1.0</ITRForm:FormVer>
    </ITRForm:Form_ITR1>
    <ITRForm:PersonalInfo>
         <ITRForm:AssesseeName>
            <ITRForm:FirstName>' . $firstName . '</ITRForm:FirstName>
            <ITRForm:MiddleName>' . $middleName . '</ITRForm:MiddleName>
            <ITRForm:SurNameOrOrgName>' . $surName . '</ITRForm:SurNameOrOrgName>
         </ITRForm:AssesseeName>
         <ITRForm:PAN>' . $panNo . '</ITRForm:PAN>
         <ITRForm:Address>
              <ITRForm:ResidenceNo>' . $ResidenceNo . '</ITRForm:ResidenceNo>
              <ITRForm:ResidenceName>' . $ResidenceName . '</ITRForm:ResidenceName>
              <ITRForm:RoadOrStreet>' . $RoadOrStreet . '</ITRForm:RoadOrStreet>
              <ITRForm:LocalityOrArea>' . $LocalityOrArea . '</ITRForm:LocalityOrArea>
              <ITRForm:CityOrTownOrDistrict>' . $cityortownordistrict . '</ITRForm:CityOrTownOrDistrict>
              <ITRForm:StateCode>' . $statecode . '</ITRForm:StateCode>
              <ITRForm:CountryCode>91</ITRForm:CountryCode>
              <ITRForm:PinCode>' . $pincode . '</ITRForm:PinCode>
          
                    <ITRForm:MobileNo>' . $itrInfo->UserInfo->MobileNumber . '</ITRForm:MobileNo>
                   
            <ITRForm:EmailAddress>' . $emailId . '</ITRForm:EmailAddress>
        </ITRForm:Address>
        <ITRForm:DOB>' . $itrInfo->UserInfo->UserDOB . '</ITRForm:DOB>
        <ITRForm:EmployerCategory>' . $employerCategory . '</ITRForm:EmployerCategory>';

        if (isset($userAadhar) && !empty($userAadhar)) {
            //$strXml .= '<ITRForm:AadhaarCardFlg>Y</ITRForm:AadhaarCardFlg>';
            $strXml .= '<ITRForm:AadhaarCardNo>' . $userAadhar . '</ITRForm:AadhaarCardNo>';
        } else {
            //$strXml .= '<ITRForm:AadhaarCardNo></ITRForm:AadhaarCardNo>';
            // $strXml .= '<ITRForm:AadhaarCardFlg>N</ITRForm:AadhaarCardFlg>';
        }


        //echo $strXml; die;

        $strXml .= '</ITRForm:PersonalInfo>';
        $strXml .= '<ITRForm:FilingStatus>';
        // $strXml .= '<ITRForm:DesigOfficerWardorCircle>'.$jurisdiction.'</ITRForm:DesigOfficerWardorCircle>';
        // $strXml .= '<ITRForm:AckNoOriginalReturn/>';
        //$strXml .= '<ITRForm:NoticeNo/>';
        // $strXml .= '<ITRForm:DefRetOrigRetFiledDate/>';
        // $strXml .= '<ITRForm:OrigRetFiledDate/>';
        //$strXml .= '<ITRForm:NoticeDateUnderSec/>';
        //$strXml .= '<ITRForm:DesigOfficerWardorCircle/>';
        $strXml .= '<ITRForm:ReturnFileSec>12</ITRForm:ReturnFileSec>';
        $strXml .= '<ITRForm:ReturnType>O</ITRForm:ReturnType>';
        $strXml .= '<ITRForm:ResidentialStatus>' . $itrInfo->UserInfo->ResidentialStatus . '</ITRForm:ResidentialStatus>';
        //$strXml .= '<ITRForm:TaxStatus>TP</ITRForm:TaxStatus>';

        if (!isset($itrInfo->UserInfo->PortugeseCivil) || (isset($itrInfo->UserInfo->PortugeseCivil) && $itrInfo->UserInfo->PortugeseCivil == 'NO')) {
            $strXml .= '<ITRForm:PortugeseCC5A>N</ITRForm:PortugeseCC5A>';
        } else {
            $strXml .= '<ITRForm:PortugeseCC5A>Y</ITRForm:PortugeseCC5A>';
            $strXml .= '<ITRForm:PANOfSpouse>' . $itrInfo->UserInfo->PanOfSpouse . '</ITRForm:PANOfSpouse>';
        }

        $strXml .= '</ITRForm:FilingStatus>';

        if ($itrInfo->Income->property_type == "S" && $itrInfo->Income->property_income < 0) {
            global $house_prop_inc;
            /*f($this->Curr_AssYear=='2015-16'){
                 $selfPropertyLimit = -200000;
            }else if($this->Curr_AssYear=='2014-15'){
                $selfPropertyLimit = -150000;
            }*/
            //$selfPropertyLimit= $house_prop_inc[$this->Curr_AssYear];// get Maximum house property income
            $selfPropertyLimit = -200000;

            if ($itrInfo->Income->property_income < $selfPropertyLimit) {
                $itrInfo->Income->property_income = $selfPropertyLimit;
            }
        }


        if ($itrInfo->UserInfo->EmployerCategory != 'GOV') {
            $itrInfo->Income->deduction_us_16 = 0;
        }

        $strXml .= '<ITRForm:ITR1_IncomeDeductions>
            <ITRForm:Salary>' . $itrInfo->Income->salary_exclude_allowances . '</ITRForm:Salary>
            <ITRForm:AlwnsNotExempt>' . $itrInfo->Income->allowances_not_exempt . '</ITRForm:AlwnsNotExempt>
            <ITRForm:PerquisitesValue>' . $itrInfo->Income->value_of_perquisites . '</ITRForm:PerquisitesValue>
            <ITRForm:ProfitsInSalary>' . $itrInfo->Income->profits_in_lieu_of_salary . '</ITRForm:ProfitsInSalary>
            <ITRForm:DeductionUs16>' . $itrInfo->Income->deduction_us_16 . '</ITRForm:DeductionUs16>
           <ITRForm:IncomeFromSal>' . $itrInfo->Income->salary_income . '</ITRForm:IncomeFromSal>
           <ITRForm:TypeOfHP>' . $itrInfo->Income->property_type . '</ITRForm:TypeOfHP>

           <ITRForm:GrossRentReceived>' . $itrInfo->Income->gross_rent . '</ITRForm:GrossRentReceived>
            <ITRForm:TaxPaidlocalAuth>' . $itrInfo->Income->tax_paid_to_local_authorities . '</ITRForm:TaxPaidlocalAuth>
            <ITRForm:AnnualValue>' . $itrInfo->Income->annual_value . '</ITRForm:AnnualValue>
            <ITRForm:StandardDeduction>' . $itrInfo->Income->annualvalue30per . '</ITRForm:StandardDeduction>
            <ITRForm:InterestPayable>' . $itrInfo->Income->int_payable_borrpwed_capital . '</ITRForm:InterestPayable>


           <ITRForm:TotalIncomeOfHP>' . $itrInfo->Income->property_income . '</ITRForm:TotalIncomeOfHP>
               <ITRForm:IncomeOthSrc>' . $itrInfo->Income->other_income . '</ITRForm:IncomeOthSrc>';
        $GrossTotIncome = ($itrInfo->Income->salary_income + $itrInfo->Income->property_income + $itrInfo->Income->other_income);
        $this->GTI = $GrossTotIncome;
        // $GrossTotIncome = ($GrossTotIncome>0)?$GrossTotIncome:0;
        $this->All_TotIncome = $GrossTotIncome;//($GrossTotIncome>0)?$GrossTotIncome:0;
        $this->arrResponce['GrossTotIncome'] = "$GrossTotIncome";
        $strXml .= '<ITRForm:GrossTotIncome>' . $GrossTotIncome . '</ITRForm:GrossTotIncome>
          <ITRForm:UsrDeductUndChapVIA>
               <ITRForm:Section80C>' . $Userdeduction->deduction_80C . '</ITRForm:Section80C>
               <ITRForm:Section80CCC>' . $Userdeduction->deduction_80CCC . '</ITRForm:Section80CCC>
               <ITRForm:Section80CCDEmployeeOrSE>' . $Userdeduction->deduction_80CCD_employee . '</ITRForm:Section80CCDEmployeeOrSE>
	       <ITRForm:Section80CCD1B>' . $Userdeduction->deduction_80CCD1B_employee . '</ITRForm:Section80CCD1B>
               <ITRForm:Section80CCDEmployer>' . $Userdeduction->deduction_80CCD_employer . '</ITRForm:Section80CCDEmployer>
               <ITRForm:Section80D>' . $Userdeduction->deduction_80D . '</ITRForm:Section80D>
               <ITRForm:Section80DD>' . $Userdeduction->deduction_80DD . '</ITRForm:Section80DD>
               <ITRForm:Section80DDB>' . $Userdeduction->deduction_80DDB . '</ITRForm:Section80DDB>
               <ITRForm:Section80EE>' . $Userdeduction->deduction_80EE . '</ITRForm:Section80EE> 
               <ITRForm:Section80G>' . $Userdeduction->tot_deduction_80G . '</ITRForm:Section80G>
               <ITRForm:Section80GG>' . $Userdeduction->deduction_80GG . '</ITRForm:Section80GG>
               <ITRForm:Section80GGA>' . $Userdeduction->deduction_80GGA . '</ITRForm:Section80GGA>
               <ITRForm:Section80GGC>' . $Userdeduction->deduction_80GGC . '</ITRForm:Section80GGC>
               <ITRForm:Section80U>' . $Userdeduction->deduction_80U . '</ITRForm:Section80U>
               <ITRForm:Section80RRB>' . $Userdeduction->deduction_80RRB . '</ITRForm:Section80RRB>
               <ITRForm:Section80QQB>' . $Userdeduction->deduction_80QQB . '</ITRForm:Section80QQB>
               <ITRForm:Section80CCG>' . $Userdeduction->deduction_80CCG . '</ITRForm:Section80CCG>
               <ITRForm:Section80TTA>' . $Userdeduction->deduction_80TTA . '</ITRForm:Section80TTA>
               <ITRForm:TotalChapVIADeductions>' . $Userdeduction->TotalChapVIADeductions . '</ITRForm:TotalChapVIADeductions>
          </ITRForm:UsrDeductUndChapVIA>';

        $sumOFIndDeductionArr['80C'] = $Userdeduction->deduction_80C;
        $sumOFIndDeductionArr['80CCC'] = $Userdeduction->deduction_80CCC;
        $sumOFIndDeductionArr['80CCD_Employee'] = $Userdeduction->deduction_80CCD_employee;
        $total80C_80CCC_80CCD_emp = $this->getAssessmentYearValue('tot80C');
        $this->reserveAmountFor80C = $total80C_80CCC_80CCD_emp;// reserve Amount for 80c,80ccc,80ccd_emp
        $this->reserveAmountFor80EE = $Userdeduction->deduction_80EE;// reserve Amount for 80c,80ccc,80ccd_emp
        $this->reserveAmountFor80CCD1B = $Userdeduction->deduction_80CCD1B_employee;

        $value80C = $this->getDeductionEligibleAmount('80C', $Userdeduction->deduction_80C, $sumOFIndDeductionArr);
        $sumOFIndDeductionArr['80C'] = $sumOFIndDeductionArr['80C'];

        $value80CCC = $this->getDeductionEligibleAmount('80CCC', $Userdeduction->deduction_80CCC, $sumOFIndDeductionArr);
        $sumOFIndDeductionArr['80CCC'] = $sumOFIndDeductionArr['80CCC'];


        $value80CCD_employee = $this->getDeductionEligibleAmount('80CCD_Employee', $Userdeduction->deduction_80CCD_employee, $sumOFIndDeductionArr);
        $sumOFIndDeductionArr['80CCD_Employee'] = $sumOFIndDeductionArr['80CCD_Employee'];

        $strXml .= '<ITRForm:DeductUndChapVIA>
               <ITRForm:Section80C>' . $value80C . '</ITRForm:Section80C>
               <ITRForm:Section80CCC>' . $value80CCC . '</ITRForm:Section80CCC>
               <ITRForm:Section80CCDEmployeeOrSE>' . $value80CCD_employee . '</ITRForm:Section80CCDEmployeeOrSE>
              <ITRForm:Section80CCD1B>' . $this->getDeductionEligibleAmount('80EE', $Userdeduction->deduction_80EE) . '</ITRForm:Section80CCD1B>
                
 <ITRForm:Section80CCD1B>' . $this->getDeductionEligibleAmount('80CCD1B', $Userdeduction->deduction_80CCD1B_employee) . '</ITRForm:Section80CCD1B>
               <ITRForm:Section80CCDEmployer>' . $this->getDeductionEligibleAmount('80CCD_Employer', $Userdeduction->deduction_80CCD_employer) . '</ITRForm:Section80CCDEmployer>
               <ITRForm:Section80D>' . $this->getDeductionEligibleAmount('80D', $Userdeduction->deduction_80D) . '</ITRForm:Section80D>
               <ITRForm:Section80DD>' . $this->getDeductionEligibleAmount('80DD', $Userdeduction->deduction_80DD) . '</ITRForm:Section80DD>
               <ITRForm:Section80DDB>' . $this->getDeductionEligibleAmount('80DDB', $Userdeduction->deduction_80DDB) . '</ITRForm:Section80DDB>
               <ITRForm:Section80E>' . $this->getDeductionEligibleAmount('80E', $Userdeduction->deduction_80E) . '</ITRForm:Section80E>
               <ITRForm:Section80G>' . $this->getDeductionEligibleAmount('80G', $Userdeduction->tot_deduction_80G) . '</ITRForm:Section80G>
               <ITRForm:Section80GG>' . $this->getDeductionEligibleAmount('80GG', $Userdeduction->deduction_80GG) . '</ITRForm:Section80GG>
               <ITRForm:Section80GGA>' . $this->getDeductionEligibleAmount('80GGA', $Userdeduction->deduction_80GGA) . '</ITRForm:Section80GGA>
               <ITRForm:Section80GGC>' . $this->getDeductionEligibleAmount('80GGC', $Userdeduction->deduction_80GGC) . '</ITRForm:Section80GGC>
               <ITRForm:Section80U>' . $this->getDeductionEligibleAmount('80U', $Userdeduction->deduction_80U) . '</ITRForm:Section80U>
               <ITRForm:Section80RRB>' . $this->getDeductionEligibleAmount('80RRB', $Userdeduction->deduction_80RRB) . '</ITRForm:Section80RRB>
               <ITRForm:Section80QQB>' . $this->getDeductionEligibleAmount('80QQB', $Userdeduction->deduction_80QQB) . '</ITRForm:Section80QQB>
               <ITRForm:Section80CCG>' . $this->getDeductionEligibleAmount('80CCG', $Userdeduction->deduction_80CCG) . '</ITRForm:Section80CCG>
               <ITRForm:Section80TTA>' . $this->getDeductionEligibleAmount('80TTA', $Userdeduction->deduction_80TTA) . '</ITRForm:Section80TTA>';


        $tot_Deduction_Eligible = $this->getDeductionEligibleAmount('tot_Deduction_Eligible', '0');
        $strXml .= '<ITRForm:TotalChapVIADeductions>' . $tot_Deduction_Eligible . '</ITRForm:TotalChapVIADeductions>
          </ITRForm:DeductUndChapVIA>';
        $this->arrResponce['totalDeduction'] = "$tot_Deduction_Eligible";
        $taxableTotIncome = (($this->All_TotIncome - $tot_Deduction_Eligible) > 0) ? ($this->All_TotIncome - $tot_Deduction_Eligible) : 0;
        $this->arrResponce['taxableTotIncome'] = "$taxableTotIncome";
        $strXml .= '<ITRForm:TotalIncome>' . $taxableTotIncome . '</ITRForm:TotalIncome>
    </ITRForm:ITR1_IncomeDeductions>
    <ITRForm:ITR1_TaxComputation>';
        // $taxableTotIncome;
        //pkdpkd
        $TotalTaxPayable = $this->getTotalTaxPayable($taxableTotIncome);
        $this->arrResponce['TotalTaxPayable'] = "$TotalTaxPayable";

        /*showing the tds detail*/
        $this->arrResponce['total_tds_sal_ded'] = "$sumTds";
        $this->arrResponce['total_tds_nonsal_ded'] = "$sumNonTdsSal";
        $this->arrResponce['total_advance_ded'] = "$sumTdsAdvance";
        /*showing the tds detail*/


        $Rebate87A = $this->getRebate87A($taxableTotIncome);
        $Rebate87A = ($Rebate87A > $TotalTaxPayable) ? $TotalTaxPayable : $Rebate87A;
        $this->arrResponce['Rebate87A'] = "$Rebate87A";
        $TaxPayableOnRebate = (($TotalTaxPayable - $Rebate87A) > 0) ? ($TotalTaxPayable - $Rebate87A) : 0;// total payable after rebate
        $this->arrResponce['TaxPayableOnRebate'] = "$TaxPayableOnRebate";
        //$SurchargeOnAboveCrore =$this->getSurchargeOnAboveCrore($taxableTotIncome,$TotalTaxPayable);
        $SurchargeOnAboveCrore = '0';

        $this->arrResponce['SurchargeOnAboveCrore'] = "$SurchargeOnAboveCrore";
        $EducationCess = $this->getEducationCess($TaxPayableOnRebate + $SurchargeOnAboveCrore);
        $this->arrResponce['EducationCess'] = "$EducationCess";
        $GrossTaxLiability = $TaxPayableOnRebate + $SurchargeOnAboveCrore + $EducationCess;
        $this->arrResponce['TotTaxSurchargeEduCess'] = "$GrossTaxLiability";


        if ($flag == 'ios') {
            $Section89 = round($itrInfo->bankinfo[1]->relief89); //$this->getSection89($GrossTaxLiability);
        } else {
            $Section89 = round($itrInfo->bankinfo->relief89); //$this->getSection89($GrossTaxLiability);
        }
        $this->arrResponce['Relief89'] = "$Section89";
        $NetTaxLiability = (($GrossTaxLiability - $Section89) > 0) ? ($GrossTaxLiability - $Section89) : 0;
        $this->arrResponce['BalanceTaxAfterRelief'] = "$NetTaxLiability";
        /*********************Pending Calcutation************************/

        $AdvanceTax = $this->getAdvanceTax($itrInfo);

        $TDS = $this->getTDS($itrInfo);
        $SelfAssessmentTax = $this->getSeftAssismentTax($itrInfo);
        $TotalTaxesPaid = $AdvanceTax + $TDS + $SelfAssessmentTax + $totalTCSPaid;//pkdpkd
        $balTaxPay = $TotTaxPlusIntrstPay - $TotalTaxesPaid;
        $BalTaxPayable = ($balTaxPay > 0) ? $balTaxPay : 0;

        $advancePlusTds = $AdvanceTax + $TDS + $totalTCSPaid;
        //print "&&&&&&&&&&&&&&& $AdvanceTax+$TDS+ $totalTCSPaid";
        $currentTaxableIncome = $NetTaxLiability - $advancePlusTds;

        //print "############## $NetTaxLiability-$advancePlusTds";
        $interest234A = $this->getInterestPay234('A', $currentTaxableIncome, $NetTaxLiability);
        $interest234B = $this->getInterestPay234('B', $currentTaxableIncome, $NetTaxLiability);
        $interest234C = $this->getInterestPay234FnC($NetTaxLiability, $TDS, $itrInfo->TaxPaid->AdvanceTax, $totalTCSPaid);

        $this->arrResponce['TotalInterest234A'] = "$interest234A";
        $this->arrResponce['TotalInterest234B'] = "$interest234B";
        $this->arrResponce['TotalInterest234C'] = "$interest234C";
        $TotalIntrstPay = ($interest234A + $interest234B + $interest234C);
        $this->arrResponce['TotalIntrstPay'] = "$TotalIntrstPay";
        /*********************Panding Calcutation************************/


        $strXml .= '<ITRForm:TotalTaxPayable>' . $TotalTaxPayable . '</ITRForm:TotalTaxPayable>
           
           <ITRForm:Rebate87A>' . $Rebate87A . '</ITRForm:Rebate87A>    
           <ITRForm:TaxPayableOnRebate>' . $TaxPayableOnRebate . '</ITRForm:TaxPayableOnRebate>
           
           <ITRForm:EducationCess>' . $EducationCess . '</ITRForm:EducationCess>
           <ITRForm:GrossTaxLiability>' . $GrossTaxLiability . '</ITRForm:GrossTaxLiability>
           <ITRForm:Section89>' . $Section89 . '</ITRForm:Section89>
           <ITRForm:NetTaxLiability>' . $NetTaxLiability . '</ITRForm:NetTaxLiability>
           <ITRForm:TotalIntrstPay>' . $TotalIntrstPay . '</ITRForm:TotalIntrstPay>                 
            <ITRForm:IntrstPay>
            <ITRForm:IntrstPayUs234A>' . $interest234A . '</ITRForm:IntrstPayUs234A>
            <ITRForm:IntrstPayUs234B>' . $interest234B . '</ITRForm:IntrstPayUs234B>
            <ITRForm:IntrstPayUs234C>' . $interest234C . '</ITRForm:IntrstPayUs234C>
            </ITRForm:IntrstPay>';
        $TotTaxPlusIntrstPay = $TotalIntrstPay + $NetTaxLiability;


        /******pankaj***/


        $refund = 0;
        $finalTaxPaid = $TotTaxPlusIntrstPay - $totalTds - $totalTCSPaid;
        if ($finalTaxPaid < 0) {
            $refund = -($finalTaxPaid);
            $finalTaxPaid = 0;
        }
        $this->arrResponce['final_tax_payable'] = "$finalTaxPaid";
        /*****end code*****/

        $this->arrResponce['BalTaxPayable'] = "$BalTaxPayable";
        $this->arrResponce['TotTaxPlusIntrstPay'] = "$TotTaxPlusIntrstPay";
        $this->arrResponce['refund'] = "$refund";

        $strXml .= '<ITRForm:TotTaxPlusIntrstPay>' . $TotTaxPlusIntrstPay . '</ITRForm:TotTaxPlusIntrstPay>
                

    </ITRForm:ITR1_TaxComputation>
    <ITRForm:TaxPaid>
          <ITRForm:TaxesPaid>
               <ITRForm:AdvanceTax>' . $AdvanceTax . '</ITRForm:AdvanceTax>
               <ITRForm:TDS>' . $TDS . '</ITRForm:TDS>
               <ITRForm:SelfAssessmentTax>' . $SelfAssessmentTax . '</ITRForm:SelfAssessmentTax>
               <ITRForm:TotalTaxesPaid>' . $TotalTaxesPaid . '</ITRForm:TotalTaxesPaid>
                <ITRForm:ExcIncSec1038>' . $itrInfo->exemptIncome->sec1038Income . '</ITRForm:ExcIncSec1038>
               <ITRForm:ExcIncSec1034>' . $itrInfo->exemptIncome->sec1034Income . '</ITRForm:ExcIncSec1034>';
        //loop for multiple		
        $strXml .= '<ITRForm:OthersInc>';

        for ($i = 0; $i < $totalexemptOtherIncome; $i++) {
            $strXml .= ' <ITRForm:OthersIncDtls>
                <ITRForm:OthNatOfInc>' . $itrInfo->TaxPaid->exemptOtherIncome[$i]->natureIncome . '</ITRForm:OthNatOfInc>
                <ITRForm:OthAmount>' . $itrInfo->TaxPaid->exemptOtherIncome[$i]->totalIncome . '</ITRForm:OthAmount>
                </ITRForm:OthersIncDtls>';
        }

        $strXml .= ' </ITRForm:OthersInc>
                 </ITRForm:TaxesPaid>
                 <ITRForm:BalTaxPayable>' . $finalTaxPaid . '</ITRForm:BalTaxPayable>
                </ITRForm:TaxPaid>
                <ITRForm:Refund>
                <ITRForm:RefundDue>' . $refund . '</ITRForm:RefundDue>
                <ITRForm:BankAccountDtls>
        
            <ITRForm:PriBankDetails>
                <ITRForm:IFSCCode>' . $itrInfo->bankinfo->ifsc_code . '</ITRForm:IFSCCode>
                <ITRForm:BankName>' . $itrInfo->bankinfo->bank_name . '</ITRForm:BankName>    
                <ITRForm:BankAccountNo>' . $itrInfo->bankinfo->account_number . '</ITRForm:BankAccountNo>
                <ITRForm:BankAccountType>' . $itrInfo->bankinfo->account_type . '</ITRForm:BankAccountType> 
     		 	
            </ITRForm:PriBankDetails>';

        for ($i = 0; $i < $totalBankAccounts; $i++) {
            $strXml .= '<ITRForm:AddtnlBankDetails>
                <ITRForm:IFSCCode>' . $itrInfo->bankinfo->bankDetail[$i]->ifsc_code . '</ITRForm:IFSCCode>
                <ITRForm:BankName>' . $itrInfo->bankinfo->bankDetail[$i]->bank_name . '</ITRForm:BankName>    
                <ITRForm:BankAccountNo>' . $itrInfo->bankinfo->bankDetail[$i]->account_number . '</ITRForm:BankAccountNo>
                <ITRForm:BankAccountType>' . $itrInfo->bankinfo->bankDetail[$i]->account_type . '</ITRForm:BankAccountType>
                   
            </ITRForm:AddtnlBankDetails>';
        }

        $strXml .= '<ITRForm:BankAccounts>' . $totalBankAccounts . '</ITRForm:BankAccounts>
        </ITRForm:BankAccountDtls>
    </ITRForm:Refund>
      <ITRForm:Schedule80G>
       <ITRForm:Don100Percent>';
        // 80GA 100%
        $totalEligible_80GA = 0;
        $totalDonation_80GA = 0;
        foreach ($itrInfo->Fill_80G->A as $k => $value80GA) {
            $eligibleDonation80GA = $this->geteligibleDonation80G('A', $value80GA->AmountOfDonation);
            $totalEligible_80GA += $eligibleDonation80GA;
            $totalDonation_80GA += $value80GA->AmountOfDonation;
            $strXml .= '<ITRForm:DoneeWithPan>
                   <ITRForm:DoneeWithPanName>' . $value80GA->NameOfDonee . '</ITRForm:DoneeWithPanName>
                   <ITRForm:DoneePAN>' . $value80GA->PanOfDonee . '</ITRForm:DoneePAN>
                   <ITRForm:AddressDetail>
                       <ITRForm:AddrDetail>' . $value80GA->Address . '</ITRForm:AddrDetail>
                       <ITRForm:CityOrTownOrDistrict>' . $value80GA->CityOrTownOrDistrict . '</ITRForm:CityOrTownOrDistrict>
                       <ITRForm:StateCode>' . $value80GA->StateCode . '</ITRForm:StateCode>
                       <ITRForm:PinCode>' . $value80GA->PinCode . '</ITRForm:PinCode>
                   </ITRForm:AddressDetail>
                   <ITRForm:DonationAmt>' . round($value80GA->AmountOfDonation) . '</ITRForm:DonationAmt>
                   <ITRForm:EligibleDonationAmt>' . $eligibleDonation80GA . '</ITRForm:EligibleDonationAmt>
               </ITRForm:DoneeWithPan>';
        }
        $strXml .= '<ITRForm:TotEligibleDon100Percent>' . $totalEligible_80GA . '</ITRForm:TotEligibleDon100Percent>
               <ITRForm:TotDon100Percent>' . $totalDonation_80GA . '</ITRForm:TotDon100Percent>
          </ITRForm:Don100Percent>';
        // 80GB
        $strXml .= '<ITRForm:Don50PercentNoApprReqd>';
        $totalEligible_80GB = 0;
        $totalDonation_80GB = 0;
        foreach ($itrInfo->Fill_80G->B as $k => $value80GB) {
            $eligibleDonation80GB = $this->geteligibleDonation80G('B', $value80GB->AmountOfDonation);
            $totalEligible_80GB += $eligibleDonation80GB;
            $totalDonation_80GB += $value80GB->AmountOfDonation;
            $strXml .= '<ITRForm:DoneeWithPan>
                   <ITRForm:DoneeWithPanName>' . $value80GB->NameOfDonee . '</ITRForm:DoneeWithPanName>
                   <ITRForm:DoneePAN>' . $value80GB->PanOfDonee . '</ITRForm:DoneePAN>
                  <ITRForm:AddressDetail>
                       <ITRForm:AddrDetail>' . $value80GB->Address . '</ITRForm:AddrDetail>
                       <ITRForm:CityOrTownOrDistrict>' . $value80GB->CityOrTownOrDistrict . '</ITRForm:CityOrTownOrDistrict>
                       <ITRForm:StateCode>' . $value80GB->StateCode . '</ITRForm:StateCode>
                       <ITRForm:PinCode>' . $value80GB->PinCode . '</ITRForm:PinCode>
                  </ITRForm:AddressDetail>
                   <ITRForm:DonationAmt>' . round($value80GB->AmountOfDonation) . '</ITRForm:DonationAmt>
                   <ITRForm:EligibleDonationAmt>' . $eligibleDonation80GB . '</ITRForm:EligibleDonationAmt>
               </ITRForm:DoneeWithPan>';
        }
        $strXml .= '<ITRForm:TotEligibleDon50Percent>' . $totalEligible_80GB . '</ITRForm:TotEligibleDon50Percent>
               <ITRForm:TotDon50PercentNoApprReqd>' . $totalDonation_80GB . '</ITRForm:TotDon50PercentNoApprReqd>
          </ITRForm:Don50PercentNoApprReqd>';

        // 80GC
        $strXml .= '<ITRForm:Don100PercentApprReqd>';
        $totalEligible_80GC = 0;
        $totalDonation_80GC = 0;
        foreach ($itrInfo->Fill_80G->C as $k => $value80GC) {
            $eligibleDonation80GC = $this->geteligibleDonation80G('C', $value80GC->AmountOfDonation);
            $totalEligible_80GC += $eligibleDonation80GC;
            $totalDonation_80GC += $value80GC->AmountOfDonation;
            $strXml .= '<ITRForm:DoneeWithPan>
                   <ITRForm:DoneeWithPanName>' . $value80GC->NameOfDonee . '</ITRForm:DoneeWithPanName>
                   <ITRForm:DoneePAN>' . $value80GC->PanOfDonee . '</ITRForm:DoneePAN>
                  <ITRForm:AddressDetail>
                       <ITRForm:AddrDetail>' . $value80GC->PanOfDonee . '</ITRForm:AddrDetail>
                       <ITRForm:CityOrTownOrDistrict>' . $value80GC->CityOrTownOrDistrict . '</ITRForm:CityOrTownOrDistrict>
                       <ITRForm:StateCode>' . $value80GC->StateCode . '</ITRForm:StateCode>
                       <ITRForm:PinCode>' . $value80GC->PinCode . '</ITRForm:PinCode>
                  </ITRForm:AddressDetail>
                   <ITRForm:DonationAmt>' . round($value80GC->AmountOfDonation) . '</ITRForm:DonationAmt>
                   <ITRForm:EligibleDonationAmt>' . $eligibleDonation80GC . '</ITRForm:EligibleDonationAmt>
               </ITRForm:DoneeWithPan>';
        }
        $strXml .= '<ITRForm:TotEligibleDon100PercentApprReqd>' . $totalEligible_80GC . '</ITRForm:TotEligibleDon100PercentApprReqd>
               <ITRForm:TotDon100PercentApprReqd>' . $totalDonation_80GC . '</ITRForm:TotDon100PercentApprReqd>
          </ITRForm:Don100PercentApprReqd>';
        // 80GD
        $strXml .= '<ITRForm:Don50PercentApprReqd>';
        $totalEligible_80GD = 0;
        $totalDonation_80GD = 0;
        foreach ($itrInfo->Fill_80G->D as $k => $value80GD) {
            $eligibleDonation80GD = $this->geteligibleDonation80G('D', $value80GD->AmountOfDonation);
            $totalEligible_80GD += $eligibleDonation80GD;
            $totalDonation_80GD += $value80GD->AmountOfDonation;
            $strXml .= '<ITRForm:DoneeWithPan>
                   <ITRForm:DoneeWithPanName>' . $value80GD->NameOfDonee . '</ITRForm:DoneeWithPanName>
                   <ITRForm:DoneePAN>' . $value80GD->NameOfDonee . '</ITRForm:DoneePAN>
                  <ITRForm:AddressDetail>
                       <ITRForm:AddrDetail>' . $value80GD->PanOfDonee . '</ITRForm:AddrDetail>
                       <ITRForm:CityOrTownOrDistrict>' . $value80GD->CityOrTownOrDistrict . '</ITRForm:CityOrTownOrDistrict>
                       <ITRForm:StateCode>' . $value80GD->StateCode . '</ITRForm:StateCode>
                       <ITRForm:PinCode>' . $value80GD->PinCode . '</ITRForm:PinCode>
                  </ITRForm:AddressDetail>
                   <ITRForm:DonationAmt>' . round($value80GD->AmountOfDonation) . '</ITRForm:DonationAmt>
                   <ITRForm:EligibleDonationAmt>' . $eligibleDonation80GD . '</ITRForm:EligibleDonationAmt>
               </ITRForm:DoneeWithPan>';
        }
        $strXml .= '<ITRForm:TotEligibleDon50PercentApprReqd>' . $totalEligible_80GD . '</ITRForm:TotEligibleDon50PercentApprReqd>
               <ITRForm:TotDon50PercentApprReqd>' . $totalDonation_80GD . '</ITRForm:TotDon50PercentApprReqd>
          </ITRForm:Don50PercentApprReqd>';

        $totalEligibleAmout_80G = $totalEligible_80GA + $totalEligible_80GB + $totalEligible_80GC + $totalEligible_80GD;
        $totalDonationAmout_80G = $totalDonation_80GA + $totalDonation_80GB + $totalDonation_80GC + $totalDonation_80GD;

        $strXml .= '<ITRForm:TotalEligibleDonationsUs80G>' . $totalEligibleAmout_80G . '</ITRForm:TotalEligibleDonationsUs80G>
           <ITRForm:TotalDonationsUs80G>' . $totalDonationAmout_80G . '</ITRForm:TotalDonationsUs80G>
      </ITRForm:Schedule80G>';

        // TDS ON SAL
        $strXml .= '<ITRForm:TDSonSalaries>';
        foreach ($itrInfo->TaxPaid->SalTds as $key => $valueTdsOnSal) {
            $strXml .= '<ITRForm:TDSonSalary>
                         <ITRForm:EmployerOrDeductorOrCollectDetl>
                             <ITRForm:TAN>' . $valueTdsOnSal->tan_employer . '</ITRForm:TAN>
                             <ITRForm:EmployerOrDeductorOrCollecterName>' . $valueTdsOnSal->name_employer . '</ITRForm:EmployerOrDeductorOrCollecterName>
                        </ITRForm:EmployerOrDeductorOrCollectDetl>
                        <ITRForm:IncChrgSal>' . $valueTdsOnSal->income_head_salary . '</ITRForm:IncChrgSal>
                        <ITRForm:TotalTDSSal>' . round($valueTdsOnSal->total_tax_deduction) . '</ITRForm:TotalTDSSal>
                     </ITRForm:TDSonSalary>';
        }
        $strXml .= '</ITRForm:TDSonSalaries>';

        $strXml .= '<ITRForm:TDSonOthThanSals>';
        foreach ($itrInfo->TaxPaid->NonSalTds as $key => $valueNonSal) {
            $strXml .= '<ITRForm:TDSonOthThanSal>
              <ITRForm:EmployerOrDeductorOrCollectDetl>
                   <ITRForm:TAN>' . $valueNonSal->tan_deductor . '</ITRForm:TAN>
                   <ITRForm:EmployerOrDeductorOrCollecterName>' . $valueNonSal->name_deductor . '</ITRForm:EmployerOrDeductorOrCollecterName>
              </ITRForm:EmployerOrDeductorOrCollectDetl>
               <ITRForm:UniqueTDSCerNo>' . $valueNonSal->tds_certificate_number . '</ITRForm:UniqueTDSCerNo>
               <ITRForm:DeductedYr>' . $valueNonSal->tds_deducted_year . '</ITRForm:DeductedYr>
               <ITRForm:TotTDSOnAmtPaid>' . round($valueNonSal->total_tax_deducted) . '</ITRForm:TotTDSOnAmtPaid>
               <ITRForm:ClaimOutOfTotTDSOnAmtPaid>' . $valueNonSal->Amount_out_of_6_claimed_for_this_year . '</ITRForm:ClaimOutOfTotTDSOnAmtPaid>
               <ITRForm:AmtClaimedBySpouse>' . round($valueNonSal->portugese_amount_claimed) . '</ITRForm:AmtClaimedBySpouse>
           </ITRForm:TDSonOthThanSal>';
        }
        $strXml .= '</ITRForm:TDSonOthThanSals>';

        //new addition for 2016-17

        $strXml .= '<ITRForm:ScheduleTCS>';
        foreach ($itrInfo->TaxPaid->Tcs as $key => $valueTcs) {

            $strXml .= '<ITRForm:TCS>
			<ITRForm:EmployerOrDeductorOrCollectDetl>
				<ITRForm:TAN>' . $valueTcs->tan_collector . '</ITRForm:TAN>
				<ITRForm:EmployerOrDeductorOrCollecterName>' . $valueTcs->name_collector . '</ITRForm:EmployerOrDeductorOrCollecterName>
			</ITRForm:EmployerOrDeductorOrCollectDetl>
			<ITRForm:TotalTCS>' . $valueTcs->total_tax_collected . '</ITRForm:TotalTCS>
			<ITRForm:AmtTCSClaimedThisYear>' . $valueTcs->amount_claimed . '</ITRForm:AmtTCSClaimedThisYear>	
			
		   </ITRForm:TCS>
		   <ITRForm:TotalSchTCS>' . $valueTcs->spouse_amount_claimed . '</ITRForm:TotalSchTCS>	';
        }
        $strXml .= '</ITRForm:ScheduleTCS>';


        $strXml .= '<ITRForm:TaxPayments>';
        foreach ($itrInfo->TaxPaid->AdvanceTax as $key => $valueAdvanceTax) {
            $strXml .= '<ITRForm:TaxPayment>
                <ITRForm:BSRCode>' . $valueAdvanceTax->bsr_code . '</ITRForm:BSRCode>
                <ITRForm:DateDep>' . $valueAdvanceTax->date_credit . '</ITRForm:DateDep>
                <ITRForm:SrlNoOfChaln>' . $valueAdvanceTax->challan_serial_no . '</ITRForm:SrlNoOfChaln>
                <ITRForm:Amt>' . $valueAdvanceTax->tax_paid . '</ITRForm:Amt>
            </ITRForm:TaxPayment>';
        }
        $strXml .= '</ITRForm:TaxPayments>
    
    <ITRForm:Verification>
          <ITRForm:Declaration>
                <ITRForm:AssesseeVerName>' . $fullname . '</ITRForm:AssesseeVerName>
                <ITRForm:FatherName>' . $fatherFullname . '</ITRForm:FatherName>
                <ITRForm:AssesseeVerPAN>' . $panNo . '</ITRForm:AssesseeVerPAN>
                <ITRForm:Capacity>SELF</ITRForm:Capacity>
          </ITRForm:Declaration>
           <ITRForm:Place>' . $cityortownordistrict . '</ITRForm:Place>
           <ITRForm:Date>' . date('Y-m-d') . '</ITRForm:Date>
    </ITRForm:Verification>';

        // new addition of 2016-17
        //$itrInfo->UserInfo->Asset_Liability
        if ($this->All_TotIncome >= 5000000) {
            if (
                $itrInfo->Asset_Liability->Particular_Asset->immovable_asset->land_amount_1a == 0 &&
                $itrInfo->Asset_Liability->Particular_Asset->immovable_asset->building_amount_1b == 0 &&
                $itrInfo->Asset_Liability->Particular_Asset->movable_asset->cash_amount_2a == 0 &&
                $itrInfo->Asset_Liability->Particular_Asset->movable_asset->jewellery_amount_2b == 0 &&

                $itrInfo->Asset_Liability->Particular_Asset->movable_asset->vehicle_amount_2c == 0 &&
                $itrInfo->Asset_Liability->Liability->liability_amount == 0

            ) {
                //
            } else {
                //$TotalImmovablMovablAssets=$itrInfo->Asset_Liability->Particular_Asset->immovable_asset->land_amount_1a + $itrInfo->Asset_Liability->Particular_Asset->immovable_asset->building_amount_1b + $itrInfo->Asset_Liability->Particular_Asset->movable_asset->cash_amount_2a + $itrInfo->Asset_Liability->Particular_Asset->movable_asset->jewellery_amount_2b + $itrInfo->Asset_Liability->Particular_Asset->movable_asset->vehicle_amount_2c ;
                $strXml .= '<ITRForm:ScheduleAL>
			<ITRForm:ImmovableAssetLand>' . $itrInfo->Asset_Liability->Particular_Asset->immovable_asset->land_amount_1a . '</ITRForm:ImmovableAssetLand>
			<ITRForm:ImmovableAssetBuilding>' . $itrInfo->Asset_Liability->Particular_Asset->immovable_asset->building_amount_1b . '</ITRForm:ImmovableAssetBuilding>
			<ITRForm:MovableAsset>
			
			  <ITRForm:CashInHand>' . $itrInfo->Asset_Liability->Particular_Asset->movable_asset->cash_amount_2a . '</ITRForm:CashInHand>
			  <ITRForm:JewelleryBullionEtc>' . $itrInfo->Asset_Liability->Particular_Asset->movable_asset->jewellery_amount_2b . '</ITRForm:JewelleryBullionEtc>
			  
			  <ITRForm:VehiclYachtsBoatsAircrafts>' . $itrInfo->Asset_Liability->Particular_Asset->movable_asset->vehicle_amount_2c . '</ITRForm:VehiclYachtsBoatsAircrafts>';

                $strXml .= '<ITRForm:TotalImmovablMovablAssets>' . $this->TotalImmovablMovablAssets($itrInfo->Asset_Liability->Particular_Asset->immovable_asset->land_amount_1a + $itrInfo->Asset_Liability->Particular_Asset->immovable_asset->building_amount_1b + $itrInfo->Asset_Liability->Particular_Asset->movable_asset->cash_amount_2a + $itrInfo->Asset_Liability->Particular_Asset->movable_asset->jewellery_amount_2b + $itrInfo->Asset_Liability->Particular_Asset->movable_asset->vehicle_amount_2c) . '</ITRForm:TotalImmovablMovablAssets>
			</ITRForm:MovableAsset>
			<ITRForm:LiabilityInRelatAssets>' . $itrInfo->Asset_Liability->Liability->liability_amount . '</ITRForm:LiabilityInRelatAssets>
		  </ITRForm:ScheduleAL>';
            }
        }


        $strXml .= '</ITR1FORM:ITR1> </ITRETURN:ITR>';
        //  echo $strXml; die('di');
        // $tot_Deduction_Eligible =$this->getDeductionEligibleAmount('tot_Deduction_Eligible','0');die;
        $this->arrResponce['Assesment_year'] = $Assesment_year;
        $this->arrResponce['PanCardNumber'] = $panNo;
        $this->arrResponce['Mobile'] = $Mobile;
        $this->arrResponce['totalTCSPaid'] = $totalTCSPaid;


        /*
           $termAcceptedVal = $dbhandler->termAcceptedVal($panNo,$Assesment_year);
           if($termAcceptedVal == 1){
               $response = array();
               $response['error']="true";
               $response['errorCode'] = "Already processed";
               $response['message']='You have already processed your e-Filing for Assessment Year '.$Assesment_year.' .For modification or any query please contact us.';
               header('Content-Type: application/json');
               echo json_encode($response);die;
           }
           */


        /*****  pankaj code end  *********/


        $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/services/upload/" . $panNo;
        $destination = $uploadpath;
        if (!file_exists($uploadpath)) {
            mkdir($uploadpath, 0777, true);
        }
        //   $content = preg_replace("/<*>/", "-", $strXml);
        //  $content =   preg_replace("#<>#is", "-", $strXml);

        $content = preg_replace("/&/", "-", $strXml);


        /*
            $content = str_replace ( '&amp;', '&', $strXml );
          $content = str_replace ( '&#039;', '\'', $content );
          $content = str_replace ( '&quot;', '"', $content );
          $content = str_replace ( '&lt;', '<', $content );
          $content = str_replace ( '&gt;', '>', $content );
        */
        $ay = explode('-', $Assesment_year);
        $filename = $panNo . '_ITR-1_' . $ay[0] . '_N_0001.xml';
        $fp = fopen($destination . '/' . $filename, "wb");
        fwrite($fp, $content);
        fclose($fp);
        // Save IN DB

        $this->arrResponce['filename'] = $filename;
        /*********************/
        $this->arrResponce['deduction'] = $this->deductions;//$this->totEligibleDeduction;
        //$this->arrResponce['Detail_Eligible_80G'] = $this->Eligible80G;

        return $this->arrResponce;

    }


    /****CALL WEB SERVICE ***/
    private function getUserInfo($panNo)
    {
        //$panNo ='AOFPR1846G';
        try {
            $serviceURL = "https://incometaxindiaefiling.gov.in/e-FilingWS/ditws/JurisdictionalAOInfo.wsdl";
            $client = new SoapClient($serviceURL);
            $params = array('panNum' => array('panNum' => $panNo));
            $response = $client->getJurisdictionalAO($params);
            //print_r($response);die;
            //$responce['jurisduction'] = 
            return $response->JurisdictionalAOInfo;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }


    // Get Deduction 
    private function getUserDeduction($itrArr)
    {
        $deductionArr = $itrArr->Deduction;
        unset($deductionArr->id);
        unset($deductionArr->assesment_year);
        unset($deductionArr->user_pancard);
        $deductionRes = array();
        foreach ($deductionArr as $key => $value) {
            $deductionRes[$key] = round($value);
        }
        //print_r($deductionRes);
        $tot80G_amount = $this->getTotal80G_amount($itrArr);
        $deductionRes['tot_deduction_80G'] = round($tot80G_amount->TotalDonationAmount);
        //$deductionRes['tot_Eligible_deduction_80G'] = $tot80G_amount->TotalEligibleAmount;
        $deductionRes['TotalChapVIADeductions'] = array_sum($deductionRes);
        return (object)$deductionRes;
    }


    private function getTotal80G_amount($itrInfo)
    {
        $donationAmount = array();
        $donationAmountEligible = array();
        foreach ($itrInfo->Fill_80G as $key => $val80GArr) {
            foreach ($val80GArr as $k => $amount80G) {
                $donationAmount[] = $amount80G->AmountOfDonation;
                $donationAmountEligible[] = $amount80G->EligibleAmountOfDonation;
            }
        }
        $totDonationAmount = array_sum($donationAmount);
        $totDonationEligibleAmount = array_sum($donationAmountEligible);

        $totAmount['TotalDonationAmount'] = $totDonationAmount;
        $totAmount['TotalEligibleAmount'] = $totDonationEligibleAmount;
        return (object)$totAmount;
    }


    private function getDeductionEligibleAmount($type, $amt, $indDeductionArr = '')
    {
        // print_r($indDeductionArr); die;

        $userdedAmount = $amt;
        $tot80C = $this->getAssessmentYearValue('tot80C');
        //$reserveAmountFor80C = 

        $sumof_tot80C = $indDeductionArr['80C'] + $indDeductionArr['80CCC'] + $indDeductionArr['80CCD_Employee'];

        switch ($type) {
            case '80C':
                if ($this->reserveAmountFor80C < $amt) {
                    $amount = $this->reserveAmountFor80C;
                } else {
                    $amount = $amt;
                }

                break;
            //$this->getDeductionEligibleAmount('deduction_80EE',$Userdeduction->deduction_80EE)

            case '80CCD1B':
                // echo $this->reserveAmountFor80CCD1B;die;
                if ($this->reserveAmountFor80CCD1B > 50000) {
                    $amount = 50000;
                } else {
                    $amount = $amt;
                }

                //  echo $amount;
                break;
            case 'deduction_80EE': //FOR Section80CCD1B
            case '80EE': //FOR Section80CCD1B
                if ($this->reserveAmountFor80EE > 50000) {
                    $amount = 50000;
                } else {
                    $amount = $amt;
                }

                break;
            case '80CCC':
                /*if($sumof_tot80C>$tot80C){
                     $amount=$tot80C -($indDeductionArr['80C']+$indDeductionArr['80CCD_Employee']);
                 }else{
                     $amount = $amt;
                 }*/
                if ($this->reserveAmountFor80C < $amt) {
                    $amount = $this->reserveAmountFor80C;
                } else {
                    $amount = $amt;
                }

                break;
            case '80CCD_Employee':
                /*if($sumof_tot80C>$tot80C){
                     $amount=$tot80C -($indDeductionArr['80C']+$indDeductionArr['80CCC']);
                }else{
                    $amount = $amt;
                }*/

                if ((double)$this->salary_income == 0) {
                    $totSalAmount = $this->All_TotIncome;
                } else {
                    $totSalAmount = $this->salary_income;
                }
                if ($this->reserveAmountFor80C < $amt) {
                    $amount = $this->reserveAmountFor80C;
                } else {
                    $amount = $amt;
                }
                $am10Per = (($totSalAmount * 10) / 100);
                $amount = ($amount > $am10Per) ? $am10Per : $amount;
                break;
            case '80CCD_Employer':
                if ((double)$this->salary_income == 0) {
                    $totSalAmount = $this->All_TotIncome;
                } else {
                    $totSalAmount = $this->salary_income;
                }
                $amount = (($totSalAmount * 10) / 100); //10% of the salary of any employee
                //if()
                $amt = ($amt > $amount) ? $amount : $amt;
                $amount = $amt;
                break;
            case '80D':
                if ($this->userAge > 80) {
                    $amt = ($amt > 60000) ? 60000 : $amt;
                } else if ($this->userAge > 60) {
                    $amt = ($amt > 60000) ? 60000 : $amt;
                } else {
                    $amt = ($amt > 55000) ? 55000 : $amt;
                }
                $amount = $amt;
                break;
            case '80DD':
                if ($amt > 0 && $amt <= 75000) {
                    $amount = 75000;
                } else if ($amt > 75000) {
                    $amount = 125000;
                } else {
                    $amount = 0;
                }
                //$amount =  ($amt>125000)?125000:$amt;
                break;
            case '80DDB':
                /*
                   if($this->userAge>80){
                        $amt = ($amt>60000)?60000:$amt;
                   }else if($this->userAge>60){
                        $amt = ($amt>60000)?60000:$amt;
                   }else{
                        $amt = ($amt>40000)?40000:$amt;
                   }
                */
                // No need of age suggested by Himanshu.
                $amt = ($amt > 80000) ? 80000 : $amt;
                $amount = $amt;
                break;
            case '80E':
                $amount = $amt;
                break;
            case '80EE':
                $amount = ($amt > 100000) ? 100000 : $amt;
                break;
            case '80G':
                $amount = $amt;
                break;
            case '80GG':
                $amount = ($amt > 60000) ? 60000 : $amt;
                break;
            case '80GGA':
                $amount = $amt;
                break;
            case '80GGC':
                $amount = $amt;
                break;
            case '80U':
                /*if($amt > 0 && $amt <=75000){
                    $amount =  75000;
                }else if($amt > 75000){
                    $amount =  125000;
                }else{
                    $amount =  0;
                }*/
                $amount = ($amt > 125000) ? 125000 : $amt;
                break;
            case '80RRB':
                $amount = $amt;
                break;
            case '80QQB':
                $amount = $amt;
                break;
            case '80CCG':
                if ($this->GTI > 1200000) {
                    $amount = 0;
                } else {
                    $amount = ($amt > 25000) ? 25000 : $amt;
                }


                break;
            case '80TTA':
                $amount = ($amt > 10000) ? 10000 : $amt;
                break;
            case 'tot_Deduction_Eligible':
                $amount = array_sum($this->totEligibleDeduction);
                break;

        }


        if ($type != 'tot_Deduction_Eligible') {
            $this->totEligibleDeduction[$type] = "$amount";
        }


        // if amount of any deduction can not be more than gross total income
        if ($amount > $this->All_TotIncome) {
            $amount = $this->All_TotIncome;
        }

        $amount = ($amount > 0) ? round($amount) : 0;


        if ($type == '80C' || $type == '80CCC' || $type == '80CCD_Employee') {
            $this->reserveAmountFor80C = ($this->reserveAmountFor80C - $amount);
        }


        if ($type != 'tot_Deduction_Eligible') {

            if ($type == 'deduction_80EE') {
                $this->deductions['80EE']['amount'] = "$userdedAmount";
            } else {
                $this->deductions[$type]['amount'] = "$userdedAmount";
            }
            $this->deductions[$type]['Calculated'] = "$amount";
        }

        return $amount;
    }


    // Get 80G Elogible Amount      
    private function geteligibleDonation80G($type, $amt)
    {
        $totDeductionArr = $this->totEligibleDeduction;
        unset($totDeductionArr['80G']);
        $totDeduction = array_sum($totDeductionArr);
        $qualifyinglimit = (($this->All_TotIncome - $totDeduction) * 10) / 100;
        switch ($type) {
            case 'A':
                $amount = $amt;
                break;
            case 'B':
                $amount = ($amt / 2);
                break;
            case 'C':
                $amount = ($amt > $qualifyinglimit) ? $qualifyinglimit : $amt;
                break;
            case 'D':
                $amount = (($amt / 2) > $qualifyinglimit) ? $qualifyinglimit : $amt / 2;
                break;
        }

        if ($amount > $this->All_TotIncome) {
            $amount = $this->All_TotIncome;
        }
        $amount = "$amount";
        $this->Eligible80G[$type][] = $amount;
        return ($amount > 0) ? round($amount) : 0;

    }


    public function getTotalTaxPayable($tottaxableamount)
    {
        $aY = $this->Curr_AssYear;
        $gender = $this->gender;
        $age = $this->userAge;
        $dbhandler = new DbHandler();
        $tottaxableamount = (double)$tottaxableamount;
        $totalRebate = $dbhandler->getRebateAmount($aY, $gender, $age);
        if ($tottaxableamount <= 500000) {
            $totalTaxableAmount = $tottaxableamount - $totalRebate;
            $taxableIncome = ($totalTaxableAmount * 5) / 100;
        } else if ($tottaxableamount > 500000 && $tottaxableamount <= 1000000) {
            $tax10perAmount = 500000 - $totalRebate;
            $tax10Amount = ($tax10perAmount * 5) / 100;
            $tax20perAmount = $tottaxableamount - 500000;
            $tax20Amount = ($tax20perAmount * 20) / 100;
            $taxableIncome = $tax10Amount + $tax20Amount;
        } else {
            $tax30perAmount = $tottaxableamount - 1000000;
            $tax30Amount = ($tax30perAmount * 30) / 100;

            $tax20perAmount = 500000;
            $tax20Amount = ($tax20perAmount * 20) / 100;

            $tax10perAmount = 500000 - $totalRebate;
            $tax10Amount = ($tax10perAmount * 5) / 100;

            $taxableIncome = $tax10Amount + $tax20Amount + $tax30Amount;


        }
        $amount = $taxableIncome;
        $amount = ($amount <= 0) ? '0' : $amount;
        return round($amount);
    }

    public function getRebate87A($tottaxableamount)
    {
        if ($tottaxableamount <= 350000) {
            return 2500;
        } else {
            return 0;
        }
    }

    public function getSurchargeOnAboveCrore($taxableTotIncome, $TotalTaxPayable)
    {
        // if total taxable amount is more tha 1 crore surcharge applicable 5%
        if ($taxableTotIncome > 10000000) {
            $amt = ($TotalTaxPayable * 12) / 100;
        } else {
            $amt = 0;
        }
        return round($amt);
    }

    // education cess 3% of the total taxable amount
    public function getEducationCess($taxincome)
    {
        $amt = ($taxincome * 3) / 100;
        return round($amt);
    }


    private function getSection89($grTotIncome)
    {
        $amt = 0;
        return round($amt);
    }

    // Calculate Advance tax
    // Seft 
    private function getAdvanceTax($itr)
    {
        $amt = 0;
        $advanceTaxArr = $itr->TaxPaid->AdvanceTax;
        $dbhandler = new DbHandler();
        $currentAssesmentYear = $this->Curr_AssYear;
        $assesYear = explode('-', $currentAssesmentYear);
        $lastFiledate = $assesYear[0] . '-03-31';
        $startFiledate = ($assesYear[0] - 1) . '-04-01';
        if (count($advanceTaxArr) > 0) {
            $advanceTax = array();
            foreach ($advanceTaxArr as $key => $value) {
                $date_credit = $value->date_credit;
                if (strstr($date_credit, '/')) {
                    $dt = explode('/', $value->date_credit);
                    $d2 = $dt[2] . '-' . $dt[1] . '-' . $dt[0];
                    //print $d2."******************<br>";
                } else if (strstr($date_credit, '-')) {
                    $dt = explode('-', $value->date_credit);
                    $d2 = $dt[0] . '-' . $dt[1] . '-' . $dt[2];
                }

                if (strtotime($lastFiledate) >= strtotime($d2) && strtotime($startFiledate) <= strtotime($d2)) {
                    $advanceTax[] = $value->tax_paid;
                }
            }
            $amt = array_sum($advanceTax);
        }
        return round($amt);
    }


    private function getSeftAssismentTax($itr)
    {
        $advanceTaxArr = $itr->TaxPaid->AdvanceTax;
        $dbhandler = new DbHandler();
        $currentAssesmentYear = $this->Curr_AssYear;
        $assesYear = explode('-', $currentAssesmentYear);
        $amt = 0;
        $lastFiledate = $assesYear[0] . '-03-31';
        if (count($advanceTaxArr) > 0) {
            $advanceTax = array();
            foreach ($advanceTaxArr as $key => $value) {
                $date_credit = $value->date_credit;
                if (strstr($date_credit, '/')) {
                    $dt = explode('/', $value->date_credit);
                    $d2 = $dt[2] . '-' . $dt[1] . '-' . $dt[0];
                    //print $d2."******************<br>";
                } else if (strstr($date_credit, '-')) {
                    $dt = explode('-', $value->date_credit);
                    $d2 = $dt[0] . '-' . $dt[1] . '-' . $dt[2];
                }
                if (strtotime($lastFiledate) < strtotime($d2)) {
                    $advanceTax[] = $value->tax_paid;
                }
            }
            $amt = array_sum($advanceTax);
        }
        return round($amt);
    }

    // calculate total tax tds sal and non sal
    private function getTDS($itr)
    {
        $tdsSal = $itr->TaxPaid->SalTds;
        $tdsNonSal = $itr->TaxPaid->NonSalTds;
        $totTDSArr = array();
        if (count($tdsSal) > 0) {
            foreach ($tdsSal as $k => $value) {
                $totTDSArr[] = $value->total_tax_deduction;
            }
        }

        if (count($tdsNonSal) > 0) {
            foreach ($tdsNonSal as $k => $value) {
                $totTDSArr[] = $value->Amount_out_of_6_claimed_for_this_year;
            }
        }

        $amt = array_sum($totTDSArr);
        return round($amt);

    }

    // Get User Age in year
    private function getUserAge($dob)
    {
        $endDate = date('Y-m-d');
        $startDate = $dob;
        $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
        $year = $days / 365.25;
        return $year;
    }


    /**
     * Get Maximum Amount Accourding to Assesment Year
     */
    private function getAssessmentYearValue($type)
    {
        $aY = $this->Curr_AssYear;
        switch ($type) {
            case 'tot80C':
                $amount = 150000;
                break;
        }
        return $amount;
    }


    //234 Int Pay 
    private function getInterestPay234($type, $taxableamount, $NetTaxLiability)
    {

        $aY = $this->Curr_AssYear;
        $yearArr = explode('-', $aY);
        $taxableamount = ROUND($taxableamount / 100) * 100;// roundof amount u/s
        $year = $yearArr[0];
        switch ($type) {
            case 'A':
                $d1 = $year . '-07-31';
                $d2 = date('Y-m-d');
                if (strtotime($d1) > strtotime($d2)) {
                    $month = 0;
                } else {
                    $month = $this->getMonthBetweenDate($d1, $d2);
                }
                $amount = ($taxableamount * $month) / 100;
                break;
            case 'B':

                $totalAmount = 0;
                $amountPaid = 0;
                $pendingAmount = $taxableamount;
                $counter = 0;
                //print "***** taxableamount = $taxableamount <br>";
                $d1 = $year . '-03-31';
                $AdvanceTaxTemp = array();

                foreach ($this->itrInfoMain->TaxPaid->AdvanceTax as $key => $valueNonSal) {
                    $date_credit = $valueNonSal->date_credit;
                    if (strstr($date_credit, '/')) {
                        $dt = explode('/', $valueNonSal->date_credit);
                        $d2 = $dt[2] . '-' . $dt[1] . '-' . $dt[0];
                        //print $d2."******************<br>";
                    } else if (strstr($date_credit, '-')) {
                        $dt = explode('-', $valueNonSal->date_credit);
                        $d2 = $dt[0] . '-' . $dt[1] . '-' . $dt[2];
                    }
                    $timeStamp = strtotime($d2);
                    $AdvanceTaxTemp[$timeStamp] = $valueNonSal;
                }
                ksort($AdvanceTaxTemp);
                $d1 = $year . '-03-31';

                foreach ($AdvanceTaxTemp as $key => $valueNonSal) {
                    $counter++;
                    $amountPaid = $valueNonSal->tax_paid;
                    $date_credit = $valueNonSal->date_credit;
                    if (strstr($date_credit, '/')) {
                        $dt = explode('/', $valueNonSal->date_credit);
                        $d2 = $dt[2] . '-' . $dt[1] . '-' . $dt[0];
                    } else if (strstr($date_credit, '-')) {
                        $dt = explode('-', $valueNonSal->date_credit);
                        $d2 = $dt[0] . '-' . $dt[1] . '-' . $dt[2];
                    }

                    //IF PAID IN Advance
                    if (strtotime($d2) < strtotime($year . '-04-01')) {

                    } else {
                        //print "*************$d1,$d2 ************<br>";
                        $month = $this->getMonthBetweenDate($d1, $d2);

                        $d1 = $d2;
                        if ($month == 0) {
                            $amountTemp = 0;
                        } else {
                            $amountTemp = ($pendingAmount * $month) / 100;
                        }

                        //print "123 (pendingAmount*month)/100 = $pendingAmount * $month / 100<br>";
                        $pendingAmount = $pendingAmount - $amountPaid;
                        //$totalAmount=$totalAmount + $amountTemp;
                        $totalAmount = $totalAmount + $amountTemp;
                    }
                }
                if ($pendingAmount > 0 && $counter > 0) {
                    $amountPaid = $pendingAmount;
                    //$totalAmount=$pendingAmount;
                    //$d1 = $year.'-03-31';
                    $d2 = date('Y') . '-' . date('m') . '-' . date('d');

                    $month = $this->getMonthBetweenDate($d1, $d2);

                    if ($month == 0) {
                        $month = $month + 1;
                    }
                    $amountTemp = ($pendingAmount * $month) / 100;
                    //print "(pendingAmount*month)/100 = $pendingAmount * $month / 100<br>";

                    $totalAmount = $totalAmount + $amountTemp;

                }

                if (count($AdvanceTaxTemp) == 0) {
                    $amountPaid = $pendingAmount;
                    $totalAmount = $pendingAmount;
                    $d1 = $year . '-03-31';
                    $d2 = date('Y') . '-' . date('m') . '-' . date('d');

                    $month = $this->getMonthBetweenDate($d1, $d2);

                    if ($month == 0) {
                        $month = $month + 1;
                    }
                    $amountTemp = ($pendingAmount * $month) / 100;
                    //print "(pendingAmount*month)/100 = $pendingAmount * $month / 100<br>";
                    //$pendingAmount=$pendingAmount-$amountPaid;
                    //$totalAmount=$totalAmount + $amountTemp;
                    $totalAmount = $amountTemp;
                }


                /*
                $d1 = $year.'-04-01';
                $d2 = date('Y-m-d');
                
                 $d3 = $year.'-08-01';
                if(strtotime($d1)>  strtotime($d2)){
                    $month = 0;
                }else{
                    $month  = $this->getMonthBetweenDate($d1,$d2);
                }
                //print "********".$str= "taxableamount=$taxableamount month=$month NetTaxLiability=$NetTaxLiability";
                $amount = ($taxableamount*$month)/100;
                $amount = ($NetTaxLiability>10000)?$amount:0;
                */
                //$amount. = $str;
                $amount = ($NetTaxLiability > 10000) ? $totalAmount : 0;
                break;
        }
        $amount = ($amount < 0) ? 0 : $amount;
        return round($amount);
    }


    /* Get 234C Interest*/
    /*
    private function getInterestPay234FnC($netlibility,$tds,$advTaxArr, $totalTCSPaid){
           $aY = $this->Curr_AssYear;
           $yearArr = explode('-',$aY);
           $year = $yearArr[0];
           $sep15date = (($year-1).'-09-17');
           $dec15date = (($year-1).'-12-17');
           $mar15date = (($year).'-03-17');
           $advTaxafteredate = ($year-1).'-03-31';
           $advTaxbeforedate = ($year).'-03-31';
           $currentAmount = $netlibility-$tds-$totalTCSPaid;
           $currentAmount = round($currentAmount/10)*10;
           // getAdvance Tax
           //print_r($advTaxArr);die;
           // print_r($advTaxArr);die;
            $advTaxbefore=array();
            foreach($advTaxArr as $k =>$value){
              $AdvTaxDate = strtotime($value->date_credit);
              if($AdvTaxDate>strtotime($advTaxafteredate) && $AdvTaxDate<=strtotime($advTaxbeforedate)){
                    if($AdvTaxDate<=strtotime($sep15date)){
                        $advTaxbefore[$sep15date][] = $value->tax_paid;
                    }else if($AdvTaxDate<=strtotime($dec15date)){
                        $advTaxbefore[$dec15date][] = $value->tax_paid;
                    }else if($AdvTaxDate<=strtotime($mar15date)){
                        $advTaxbefore[$mar15date][] = $value->tax_paid;
                    }
              }
            }
            $sumArr_1 = (array_sum($advTaxbefore[$sep15date]));
            $sumArr_2 = (array_sum($advTaxbefore[$dec15date]));
            $sumArr_3 = (array_sum($advTaxbefore[$mar15date]));
           
           // echo ($currentAmount*30)/100;
           
          
            $slab30per = ((($currentAmount*30)/100-($sumArr_1))*3)/100;
            $slab30per = ($slab30per>0)?$slab30per:0;    
            $slab60per = ((($currentAmount*60)/100 - ($sumArr_1+$sumArr_2))*3)/100;
            $slab60per  = ($slab60per >0)?$slab60per :0;
            
            $slab100per = ((($currentAmount*100)/100 - ($sumArr_1+$sumArr_2+$sumArr_3))*1)/100;
            $slab100per = ($slab100per>0)?$slab100per:0;
            //echo $slab100per;die;
            //print_r($advTaxbefore);
           $totInterst234C = round($slab30per)+round($slab60per)+round($slab100per);
           $totInterst234C = ($netlibility>10000)?$totInterst234C:0;
           
           return $totInterst234C;
            
            
    }*/

    private function getInterestPay234FnC($netlibility, $tds, $advTaxArr, $totalTCSPaid)
    {
        $aY = $this->Curr_AssYear;
        $yearArr = explode('-', $aY);
        $year = $yearArr[0];
        $jun15date = (($year - 1) . '-06-16');
        $sep15date = (($year - 1) . '-09-16');
        $dec15date = (($year - 1) . '-12-16');
        $mar15date = (($year) . '-03-16');
        $advTaxafteredate = ($year - 1) . '-03-31';
        $advTaxbeforedate = ($year) . '-03-31';
        $currentAmount = $netlibility - $tds - $totalTCSPaid;
        $currentAmount = round($currentAmount / 10) * 10;
        // getAdvance Tax
        //print_r($advTaxArr);die;
        // print_r($advTaxArr);die;
        $advTaxbefore = array();
        foreach ($advTaxArr as $k => $value) {
            $AdvTaxDate = strtotime($value->date_credit);
            if ($AdvTaxDate > strtotime($advTaxafteredate) && $AdvTaxDate <= strtotime($advTaxbeforedate)) {
                if ($AdvTaxDate <= strtotime($jun15date)) {
                    $advTaxbefore[$jun15date][] = $value->tax_paid;
                } else if ($AdvTaxDate <= strtotime($sep15date)) {
                    $advTaxbefore[$sep15date][] = $value->tax_paid;
                } else if ($AdvTaxDate <= strtotime($dec15date)) {
                    $advTaxbefore[$dec15date][] = $value->tax_paid;
                } else if ($AdvTaxDate <= strtotime($mar15date)) {
                    $advTaxbefore[$mar15date][] = $value->tax_paid;
                }
            }
        }

        $sumArr_0 = (array_sum($advTaxbefore[$jun15date])); // 15 june   /* added 20/4/2017
        $sumArr_1 = (array_sum($advTaxbefore[$sep15date])); // 15 sept
        $sumArr_2 = (array_sum($advTaxbefore[$dec15date])); // 15 dec
        $sumArr_3 = (array_sum($advTaxbefore[$mar15date])); // 15 march


        // 15 % june
        $slab15per = ((($currentAmount * 15) / 100 - ($sumArr_0)) * 3) / 100;
        $slab15per = ($slab15per > 0) ? $slab15per : 0;
        // new changes 45 %
        $slab30per = ((($currentAmount * 45) / 100 - ($sumArr_0 + $sumArr_1)) * 3) / 100;
        $slab30per = ($slab30per > 0) ? $slab30per : 0;

        //new slab 75%
        $slab60per = ((($currentAmount * 75) / 100 - ($sumArr_0 + $sumArr_1 + $sumArr_2)) * 3) / 100;
        $slab60per = ($slab60per > 0) ? $slab60per : 0;

        $slab100per = ((($currentAmount * 100) / 100 - ($sumArr_0 + $sumArr_1 + $sumArr_2 + $sumArr_3)) * 1) / 100;
        $slab100per = ($slab100per > 0) ? $slab100per : 0;
        //echo $slab100per;die;
        //print_r($advTaxbefore);
        $totInterst234C = round($slab15per) + round($slab30per) + round($slab60per) + round($slab100per);
        $totInterst234C = ($netlibility > 10000) ? $totInterst234C : 0;

        return $totInterst234C;


    }

    // get Total months between two dates
    private function getMonthBetweenDate($d1, $d2)
    {
        //remove function because not giving proper result.
        /*
        $date1  = $d1;
        $date2  = $d2;
        $output = [];
        $time   = strtotime($date1);
        $last   = date('m-Y', strtotime($date2));
        do {
            $month = date('m-Y', $time);
            $total = date('t', $time);

            $output[] = [
                'month' => $month,
                'total' => $total,
            ];

            $time = strtotime('+1 month', $time);
        } while ($month != $last);

        return count($output);
        */


        //Below function is proper working.
        $date1 = $d1;
        $date2 = $d2;
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        return abs($diff);

    }

    function TotalImmovablMovablAssets($total)
    {
        return $total;
    }
//end function
}

// end class
