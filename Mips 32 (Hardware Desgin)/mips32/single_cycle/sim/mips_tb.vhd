-- mips 32_bit single_cycle_processor
library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;
entity mips_tb is  -- mips 32_bit single_cycle_processor  
    end entity;
    
    architecture rtl of mips_tb is
    signal clk,reset,reg_clk_read,reg_clk_write,mem_clk_read,mem_clk_write : 
        std_logic := '0';
    ----------------------------------------------------------------------
    --                              PC                                  --
    ----------------------------------------------------------------------    
    signal pc_in,pc_out : 
        std_logic_vector(31 downto 0) := (others => '0');
    ----------------------------------------------------------------------


    ----------------------------------------------------------------------
    --                           INST_MEM                               --
    ----------------------------------------------------------------------    
    signal  im_read_adr :
        std_logic_vector(31 downto 0) := (others => '0');
    signal im_instruction :
        std_logic_vector(31 downto 0);
    ----------------------------------------------------------------------

    ----------------------------------------------------------------------
    --                        REG_FILE                                  --
    ----------------------------------------------------------------------    
    signal rf_read_reg1,rf_read_reg2,rf_write_reg : 
        std_logic_vector(4 downto 0);
    signal rf_read_data1,rf_read_data2,rf_write_data :
        std_logic_vector(31 downto 0);
    signal rf_reg_write :
        std_logic;
    ----------------------------------------------------------------------

    ----------------------------------------------------------------------
    --                             ALU                                  --
    ----------------------------------------------------------------------
    signal alu_src1,alu_src2,alu_result : 
        std_logic_vector(31 downto 0);
    signal alu_bcond : 
        std_logic;
    signal alu_ctrl :
        std_logic_vector(5 downto 0);  
    ----------------------------------------------------------------------

    signal sign_extend_out : std_logic_vector(31 downto 0);

    ----------------------------------------------------------------------
    --                           DATA_MEM                               --
    ---------------------------------------------------------------------- 
    signal dm_mem_write,dm_mem_read : 
        std_logic;
    signal dm_adr,dm_read_data,dm_write_data : 
        std_logic_vector(31 downto 0);
    ----------------------------------------------------------------------
    
    ----------------------------------------------------------------------
    --                          MAIN_CONTROL                            --
    ----------------------------------------------------------------------
    signal alu_op : std_logic_vector(5 downto 0);
    signal reg_dst,jump,branch ,mem_read,mem_to_reg,
        mem_write,alu_src,reg_write : 
            std_logic; 
    ----------------------------------------------------------------------
    
    ----------------------------------------------------------------------
    --                        NXT_INSTRUCTION                           --
    ----------------------------------------------------------------------
    signal main_adder_out,br_adder_out : std_logic_vector(31 downto 0);
    signal br_mux_out : std_logic_vector(31 downto 0);
    ----------------------------------------------------------------------
---------------------------------------------------------------------------------------------------
begin    

    ----------------------------------------------------------------------
    --                        CLKS_GENERATing                           --
    ----------------------------------------------------------------------
    MAIN_CLK_GEN : process is
    begin
        wait for 250 ps;
        clk <= not clk;
    end process;

    RF_READ_CLK : process is
    begin
        wait for 50 ps;
        while(true) loop
            reg_clk_read <= not reg_clk_read;
            wait for 250 ps;
        end loop; 
    end process;

    MEM_READ_CLK : process is
    begin
        wait for 100 ps;
        while(true) loop
            mem_clk_read <= not mem_clk_read;
            wait for 250 ps;
        end loop;
    end process;

    MEM_WRITE_CLK : process is
    begin
        wait for 150 ps;
        while(true) loop
            mem_clk_write <= not mem_clk_write;
            wait for 250 ps;
        end loop;
    end process;

    RF_WRITE_CLK : process is
        begin
            wait for 200 ps;
            while(true) loop
                reg_clk_write <= not reg_clk_write;
                wait for 250 ps;
            end loop;
    end process;

    ----------------------------------------------------------------------  



----------------------------------------------------------------------
--                      FETCH_INSTRUCTION                           --
----------------------------------------------------------------------

        PC : process(clk,reset)
            begin
                if(reset = '1') then 
                    pc_out <= (others => '0');
                else if(rising_edge(clk)) then
                    pc_out <= pc_in;
                end if;
                end if;
        end process;

        MAIN_ADDER : entity work.alu(rtl) 
        generic map(
            INPUT_WIDTH => 32
        )
        port map(
            -- inputs
            in1 => pc_out,
            in2 => std_logic_vector(to_unsigned(4,32)),
            control => "000010",
            -- outputs
            alu_result => main_adder_out,
            zero => open
        );

        ADDER_BRANCH : 
        entity work.alu(rtl) 
        generic map(
            INPUT_WIDTH => 32
        )
        port map(
            -- inputs
            in1 => main_adder_out,
            in2 => (sign_extend_out sll 2),
            control => "000010",
            -- outputs
            alu_result => br_adder_out,
            zero => open
        );

        MUX_BRCH :
        entity work.mux(rtl)
        generic map(
            WIDTH => 32
        )
        port map(
            -- inputs
            in1 => main_adder_out,
            in2 => br_adder_out,
            sel => branch and alu_bcond,
            -- outputs
            o => br_mux_out
        );

        MUX_JUMP :
        entity work.mux(rtl)
        generic map(
            WIDTH => 32
        )
        port map(
            -- inputs
            in1 => br_mux_out,
            in2 => (main_adder_out(31 downto 28) & (("00" & im_instruction(25 downto 0))  sll 2)),
            sel => jump,
            -- outputs
            o => pc_in
        );

        INST_MEM : entity work.inst_mem(rtl) 
        generic map(
            ADR_WIDTH => 32,
            INSTRUCTION_WIDTH => 32
        )
        port map(
            -- inputs
            read_add => im_read_adr,
            -- outputs
            inst => im_instruction
        );
        im_read_adr <= pc_out;
        
    -----------------------------------------------------------------------------------------


    ----------------------------------------------------------------------
    --                          MAIN_CONTROL                            --
    ----------------------------------------------------------------------
        MAIN_CONTROL : entity work.main_control(rtl) 
        port map(
            op_code => im_instruction(31 downto 26),
            ctrls(13) => reg_dst,
            ctrls(12) => jump,
            ctrls(11) => branch,
            ctrls(10) => mem_read,
            ctrls(9)  => mem_to_reg,
            ctrls(8 downto 3) => alu_op,
            ctrls(2) => mem_write,
            ctrls(1) => alu_src,
            ctrls(0) => reg_write
        );


    ----------------------------------------------------------------------


    ----------------------------------------------------------------------
    --                          INST_DEC && REG_FET                     --
    ----------------------------------------------------------------------
        REG_DIST_MUX : entity work.mux(rtl) 
        generic map(
            WIDTH => 5
        )
        port map(
            -- inputs
            in1 => im_instruction(20 downto 16),
            in2 => im_instruction(15 downto 11),
            sel => reg_dst,
            -- outputs
            o => rf_write_reg
        );

        REG_FILE : entity work.reg_file(rtl)
        generic map(
            REG_WIDTH => 32,
            ADR_WIDTH => 5
        )
        port map(
            -- inputs 
            clk_read => reg_clk_read,
            read_reg1 => rf_read_reg1,
            read_reg2 => rf_read_reg2,

            clk_write => reg_clk_write,
            write_reg => rf_write_reg,
            write_data => rf_write_data,
            write_en =>  rf_reg_write,
            -- outputs
            read_data1 => rf_read_data1,
            read_data2 => rf_read_data2
        );
        rf_read_reg1 <= im_instruction(25 downto 21);
        rf_read_reg2 <= im_instruction(20 downto 16);
        rf_reg_write <= reg_write;

        SIGN_EXT : entity work.sign_extend(rtl) 
        generic map(
            IMM_WIDTH => 16,
            EX_IMM_WIDTH => 32
        )
        port map(
            -- inputs
            imm_const => im_instruction(15 downto 0),
            -- outputs 
            ex_imm_const => sign_extend_out
        );
    ----------------------------------------------------------------------





    ----------------------------------------------------------------------
    --                          EXCUATION && EVALUATE_MEM               --
    ----------------------------------------------------------------------
    MUX_ALU_SRC : entity work.mux(rtl) 
    generic map(
        WIDTH => 32
    )
    port map(
        -- inputs
        in1 => rf_read_data2,
        in2 => sign_extend_out,
        sel => alu_src,
        -- outputs
        o => alu_src2
    );
    ------------------------------------
    --          ALU CONTROL           --
    ------------------------------------
    ALU_CTR : entity work.alu_cont(rtl)
    port map(
        -- inputs 
        alu_op => alu_op,
        r_func => im_instruction(5 downto 0),
        -- outputs
        alu_ctr => alu_ctrl 
    );
    ------------------------------------
    ALU_OPERATIONS : entity work.alu(rtl) 
    generic map(
        INPUT_WIDTH => 32
    )
    port map(
        -- inputs
        in1 => alu_src1,
        in2 => alu_src2,
        control => alu_ctrl,
        -- outputs
        alu_result => alu_result,
        zero => alu_bcond
    );
    alu_src1 <= rf_read_data1;
    ----------------------------------------------------------------------

    ----------------------------------------------------------------------
    --                       STORE && WRITE_BACK                        --
    ----------------------------------------------------------------------
    DATA_MEM : entity work.data_mem(rtl)
    generic map(
        ADR_WIDTH => 32,    
        DATA_WIDTH => 32                
    )
    port map(
        -- inputs
        clk_read => mem_clk_read,
        clk_write => mem_clk_write,
        addresse => dm_adr,
        write_data => dm_write_data,
        read_en => dm_mem_read,
        write_en => dm_mem_write,
        -- outputs
        read_data => dm_read_data
    );

    dm_adr <= alu_result;
    dm_write_data <= rf_read_data2;
    dm_mem_read <= mem_read;
    dm_mem_write <= mem_write;

    MUX_TO_REGFILE : entity work.mux(rtl)
    generic map(
        WIDTH => 32
    )
    port map(
        -- inputs
        in1 => alu_result,
        in2 => dm_read_data,
        sel => mem_to_reg,
        -- outputs
        o => rf_write_data
    );
    ----------------------------------------------------------------------
end architecture;