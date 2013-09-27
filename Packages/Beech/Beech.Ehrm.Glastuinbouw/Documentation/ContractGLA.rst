=======================
contract template  GLA
=======================

      '10000':     'Employment, job title and place of work'
        required: TRUE

        Fields
            startdate
            endDate
            jobtitle
    -
      '10001':  ' Probation'
        required: TRUE

    Fields   probationPeriod
          <day week>   = unitOfTime
    -
      '10002':   working hours
        required: TRUE
        Fields
            hours a week
            workdays
            startTime
            endTime

    -
      '10003': Payment
        required: TRUE
        fields
            minimumWage
            wage
    -
      '10004': CAO
        required: TRUE
            is a about  CAO but hardcoded!!
        -
      '10005': 'Vacation allowance and vacation days
        required: TRUE

   -
      '10006':  Sickness
        required: TRUE

    -
      '10007': Interim denunciation'
        required: TRUE
    -
      '10008':  generalCompanyRules
        required: FALSE
    -
      '10009': Identification requirement
        required: TRUE
    -
      '10010':  Accomodation
        required: FALSE
    -
      '10011': Confidentiality agreement
        required: FALSE
    -
      '10012': work clothing
        required: FALSE
    -
      '10035': Exchange of data
        required: TRUE